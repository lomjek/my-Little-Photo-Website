/*******************************************************/
/*   This file is part of 'my Little Photo Website'    */
/* It is published on github under the LGPLv3 License: */
/*  https://github.com/lomjek/my-Little-Photo-Website  */
/*******************************************************/

// @ {} [] # \ || != ~

module libs.process_image;

import std.stdio : write, writeln;
import std.file : exists, dirEntries, remove, copy, tempDir, SpanMode;
import std.path : buildPath, baseName, stripExtension, dirName;
import std.process : execute, executeShell, escapeShellCommand;
import std.string : strip;
import std.array : split, array;
import std.format : format;
import std.uuid : randomUUID;
import std.conv : to;
import std.algorithm : canFind, filter, map, startsWith;

import libs.root_conf;

struct img_size
{
	uint x, y;
}

img_size[double] aspect_ratios;
double[7] aspect_ratio_index;

// MARK: Get Paths
string generate_img_path(string collection, string file_name)
{
	string path = buildPath(root_path, "photos", collection, file_name ~ ".webp");
	if (!exists(path))
	{
		return path;
	}

	uint count = 1;
	while (exists(path))
	{
		path = buildPath(root_path, "photos", collection, file_name ~ format("_%u.webp", count));
		count++;
	}

	return path;
}

string generate_thumbnail_path(string img_path)
{
	return buildPath(dirName(img_path), ".t_" ~ baseName(img_path));
}

img_size get_original_res(string path)
{
	auto ffmpeg = execute([
		"nice", "-n", "15",
		"ffprobe",
		"-v", "error", //Only print errors, if they happened...
		"-select_streams", "v:0", //Read the first visual stream...
		"-show_entries", "stream=width,height", //Get the correct info
		"-of", "csv=s=x:p=0", //Tell ffmpeg how to format it.
		path
	]);

	if (ffmpeg.status != 0)
	{
		writeln("Something went wrong");
		return img_size(0, 0);
	}
	string result = strip(ffmpeg.output);
	return img_size(result.split("x")[0].to!uint, cast(uint) result.split("x")[1].to!uint);
}

// MARK: Process Thumbnail
double get_closes_thumb_ar(img_size original_size)
{
	double original_aspect_ratio = cast(double) original_size.x / original_size.y;
	double[7] aspect_ratio_distance;

	//Get the distance to all possible aspect ratios
	for (uint i = 0; i < 7; i++)
	{
		double aspect_ratio = aspect_ratio_index[i];
		double distance = original_aspect_ratio - aspect_ratio;
		if (distance < 0)
		{
			distance *= -1;
		}
		aspect_ratio_distance[i] = distance;
	}

	double best_aspect_ratio = 999;
	double best_distance = 999;
	for (uint i = 0; i < 7; i++)
	{
		if (aspect_ratio_distance[i] < best_distance)
		{
			best_distance = aspect_ratio_distance[i];
			best_aspect_ratio = aspect_ratio_index[i];
		}
	}
	return best_aspect_ratio;
}

bool crop_img_to_ar(string input_path, double ar, img_size original_size, string output_path)
{
	img_size output_size = img_size(0, 0);

	if ((cast(double) original_size.x / original_size.y) > ar)
	{
		output_size.x = cast(int)(original_size.y * ar);
		output_size.y = original_size.y;
	}
	else
	{
		output_size.x = original_size.x;
		output_size.y = cast(int)(original_size.x / ar);
	}

	auto ffmpeg = execute([
		"ffmpeg",
		"-y",
		"-i", input_path,
		"-c:v", "libwebp",
		"-q:v", "100",
		"-vf", format("crop=%d:%d", output_size.x, output_size.y),
		output_path
	]);
	return ffmpeg.status == 0;
}

bool process_thumbnail(string input_path, string main_img_path)
{
	string temp_path = buildPath(tempDir(), randomUUID().toString() ~ ".webp");
	img_size original_img_size = get_original_res(input_path);
	double final_aspect_ratio = get_closes_thumb_ar(original_img_size);
	img_size max_img_size = aspect_ratios[final_aspect_ratio];

	bool crop = crop_img_to_ar(
		input_path,
		final_aspect_ratio,
		get_original_res(input_path),
		temp_path
	);

	if (!crop)
	{
		if (exists(temp_path))
			remove(temp_path);
		return false;
	}

	if (original_img_size.x < max_img_size.x)
	{
		move_file(temp_path, generate_thumbnail_path(main_img_path));
		return true;
	}

	auto ffmpeg = execute([
		"ffmpeg",
		"-y",
		"-i", temp_path,
		"-c:v", "libwebp",
		"-q:v", "80",
		"-vf", format("scale=%u:%u", max_img_size.x, max_img_size.y),
		generate_thumbnail_path(main_img_path)
	]);
	remove(temp_path);
	return ffmpeg.status == 0;
}

// MARK: Process Image
bool process_image(string input_path, string collection, string file_name)
{
	string main_img_path = generate_img_path(collection, file_name);
	auto ffmpeg = execute([
		"ffmpeg",
		"-y",
		"-i", input_path,
		"-c:v", "libwebp",
		"-q:v", "95",
		main_img_path
	]);

	writeln(escapeShellCommand([
			"ffmpeg",
			"-y",
			"-i", input_path,
			"-c:v", "libwebp",
			"-q:v", "95",
			main_img_path
		]));

	if (ffmpeg.status != 0)
		return false;

	bool success = process_thumbnail(input_path, main_img_path);
	remove(input_path);
	return success;
}

// MARK: Rebuild thumb
void move_file(string input_path, string output_path)
{
	if (input_path.exists)
	{
		copy(input_path, output_path);
		remove(input_path);
	}
}

bool regenerate_thumbnail(string collection, string file)
{
	string abs_thumb_path = buildPath(root_path, "photos", collection, ".t_" ~ file ~ ".webp");
	if (abs_thumb_path.exists)
		remove(abs_thumb_path);
	string img_path = buildPath(root_path, "photos", collection, file ~ ".webp");
	return process_thumbnail(
		img_path,
		img_path
	);
}

string[] get_collections()
{
	return dirEntries(buildPath(root_path, "photos"), SpanMode.shallow)
		.filter!(e => e.isDir)
		.map!(e => baseName(e.name))
		.array;
}

bool regenerate_thumbnails()
{
	bool success = true;
	foreach (collection_name; get_collections())
	{
		string collection_path = buildPath(root_path, "photos", collection_name);
		string[] files = dirEntries(collection_path, SpanMode.shallow)
			.filter!(entry => !entry.name.baseName.startsWith("."))
			.map!(entry => entry.name.baseName.stripExtension)
			.array;

		foreach (file_name; files)
		{
			bool current_success = regenerate_thumbnail(collection_name, file_name);
			if (success && current_success)
				success = true;
			else
				success = false;
		}
	}

	return success;
}

// MARK: MAIN
void main(string[] args)
{
	aspect_ratios = [
		700.0 / 525: img_size(700, 525),
		1405.0 / 525: img_size(1405, 525), // Wide images
		2110.0 / 525: img_size(2110, 525),
		2815.0 / 525: img_size(2815, 525),
		700.0 / 1055: img_size(700, 1055), // High images
		700.0 / 1585: img_size(700, 1585),
		700.0 / 2115: img_size(700, 2115)
	];

	uint index = 0;
	foreach (double key, img_size value; aspect_ratios)
	{
		aspect_ratio_index[index] = key;
		index++;
	}

	if (args.length == 2 && args[1].strip == "regenerate_thumbnails")
	{
		write(regenerate_thumbnails());
		return;
	}

	if (args.length == 4)
	{
		string input_file = args[1].strip;
		if (!exists(input_file))
		{
			throw new Exception("The input file is not valid.");
		}

		string collection = args[2].strip;
		if (!get_collections().canFind(collection))
		{
			throw new Exception("The collection provided does not exist.");
		}

		string img_name = args[3].strip;

		write(process_image(input_file, collection, img_name));
		return;
	}

	writeln("Your run did nothing. Not right arguments provided.");
	write(false);
}
