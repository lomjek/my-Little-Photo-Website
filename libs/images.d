/*******************************************************/
/*   This file is part of 'my Little Photo Website'    */
/* It is published on github under the LGPLv3 License: */
/*  https://github.com/lomjek/my-Little-Photo-Website  */
/*******************************************************/

// @ {} [] # \ || != ~

module libs.images;

import std.file : exists, rename;
import std.process : execute;
import std.path : buildPath, dirName, baseName, stripExtension;
import std.format : format;
import std.string : strip;
import std.stdio : write, writeln;

import libs.root_conf;

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

string generate_description_path(string img_path)
{
	return buildPath(dirName(img_path), ".d_" ~ baseName(img_path) ~ ".md");
}

string rename_image(string collection, string image, string new_image_name)
{
	string photos_dir = buildPath(root_path, "photos", collection);

	string image_path = buildPath(photos_dir, image);
	string thumbnail_path = buildPath(photos_dir, ".t_" ~ image);
	string description_path = buildPath(photos_dir, ".d_" ~ image ~ ".md");

	string new_image_path = generate_img_path(collection, new_image_name.stripExtension);
	string new_thumbnail_path = generate_thumbnail_path(new_image_path);
	string new_description_path = generate_description_path(new_image_path);

	if (exists(image_path))
	{
		rename(image_path, new_image_path);
	}
	else
	{
		return "";
	}

	if (exists(thumbnail_path))
	{
		rename(thumbnail_path, new_thumbnail_path);
	}

	if (exists(description_path))
	{
		rename(description_path, new_description_path);
	}

	string iod_binary = buildPath(root_path, "libs", "iod.dc");
	auto res = execute(iod_binary);

	if (res.status == 0 && res.output.length > 0 && res.output.strip == image_path)
	{
		execute([iod_binary, "override", image_path]);
	}

	return new_image_path.baseName;
}

void main(string[] args)
{
	if (args.length == 5)
	{
		if (args[1] == "rename_image")
		{
			string ret = rename_image(args[2], args[3], args[4]);
			write(ret);
		}
	}
}
