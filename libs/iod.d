/*******************************************************/
/*   This file is part of 'my Little Photo Website'    */
/* It is published on github under the LGPLv3 License: */
/*  https://github.com/lomjek/my-Little-Photo-Website  */
/*******************************************************/

import std;

import libs.root_conf;

const string config_path = buildPath(root_path, "data", "iod.conf");
string iod_date = "";
string iod_path = "";

const string lib_preset = buildPath(root_path, "photos");

string[] get_public_collections()
{
	return dirEntries(buildPath(root_path, "photos"), SpanMode.shallow)
		.filter!(e => e.isDir)
		.filter!(e => !exists(buildPath(e.name, ".u_")))
		.map!(e => baseName(e.name))
		.array;
}

string get_image(int seed = std.conv.to!int(get_current_date()))
{
	auto rnd = Random(seed);

	//Get directory
	string[] directories = get_public_collections();
	auto randomNumber = uniform(1, directories.length + 1, rnd);
	string directory = directories[randomNumber - 1];

	//Get file
	string path = buildPath(root_path, "photos", directory);

	string[] files = dirEntries(path, SpanMode.shallow)
		.filter!(entry => !entry.name.baseName.startsWith("."))
		.map!(entry => entry.name.baseName)
		.array;

	if (files.length < 0)
		return get_image(seed - 1000); //If there is no image in this collection, find another one from 1000 ago...

	auto randomFileIndex = uniform(0, files.length, rnd);
	string file = files[randomFileIndex];

	return "photos/" ~ directory ~ "/" ~ file;
}

string get_current_date()
{
	return Clock.currTime().toISOString()[0 .. 8];
}

bool needs_reload()
{
	return (get_current_date() != iod_date) || !exists(iod_path) || !isFile(iod_path);
}

void write_iod_to_file()
{
	auto conf_file = File(config_path, "w");
	conf_file.writeln([iod_date, iod_path].joiner(":").array);
	conf_file.close();
}

void main(string[] args)
{
	if (args.length > 1)
	{ //Handle force override
		iod_date = get_current_date();
		iod_path = args[1];
		write_iod_to_file();
		write(iod_path);
		return;
	}

	if (exists(config_path))
	{
		auto conf_file = File(config_path, "r");
		string content = conf_file.readln();
		conf_file.close();
		iod_date = content.split(":")[0];
		iod_path = content.split(":")[1];
	}
	else
	{
		iod_date = get_current_date();
		write_iod_to_file();
	}

	if (needs_reload())
	{
		iod_date = get_current_date();
		iod_path = get_image();
		write_iod_to_file();
	}
	write(iod_path);
}
