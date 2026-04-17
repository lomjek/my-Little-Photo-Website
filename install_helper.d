/*******************************************************/
/*   This file is part of 'my Little Photo Website'    */
/* It is published on github under the LGPLv3 License: */
/*  https://github.com/lomjek/my-Little-Photo-Website  */
/*******************************************************/

// @ {} [] # \ || != ~

module install_helper;

import std;
import libs.root_conf;

void add_port_to_listen(string port)
{
	string ports_file = buildPath("/", "etc", "apache2", "ports.conf");
	execute(["sudo", "sh", "-c", "echo \"\nListen " ~ port ~ "\" >> " ~ ports_file]);
}

void generateConfigFile(string file_name)
{
	write("Select port to host on: ");
	string port = readln().strip();

	try
	{
		uint port_u = to!uint(port);
		enum max_port_ = 65_535;

		if (port_u < 1 || port_u > max_port_)
		{
			writeln("The port is not valid. Please choose a port between 1 and 65535.");
			return;
		}

		string config = format("<VirtualHost *:%s>\n", port);
		config ~= "    ServerName localhost\n";
		config ~= format("    DocumentRoot %s\n", root_path);
		config ~= "    DirectoryIndex index.html index.php main.html main.php\n";
		config ~= format("    <Directory %s>\n", root_path);
		config ~= "        AllowOverride All\n";
		config ~= "        Require all granted\n";
		config ~= "    </Directory>\n";
		config ~= "</VirtualHost>\n";

		string filename = format("/etc/apache2/sites-available/%s.conf", file_name);
		string temp_filename = "/tmp/" ~ file_name ~ ".conf";

		std.file.write(temp_filename, config);
		execute(["sudo", "mv", temp_filename, filename]);
		execute(["sudo", "chown", "root:root", filename]);

		execute([
				"sudo", "a2ensite", file_name ~ ".conf"
			]);

		add_port_to_listen(port);
		writeln("Configuration file generated and site enabled.");
	}
	catch (ConvException)
	{
		writeln("The port is not valid.");
	}
}

void handle_users()
{
	writeln("Setting up admin user system");
	string htaccess_path = buildPath(root_path, "update", ".htaccess");
	string htpasswd_path = buildPath(root_path, "update", ".htpasswd");

	if (exists(htaccess_path))
	{
		string[] lines = readText(htaccess_path).splitLines.array;

		if (lines.length >= 3)
		{
			lines[2] = "AuthUserFile " ~ htpasswd_path;
		}
		else
		{
			while (lines.length < 3)
			{
				lines ~= "";
			}
			lines[2] = "AuthUserFile " ~ htpasswd_path;
		}

		std.file.write(htaccess_path, lines.join("\n"));
	}

	if (!exists(htpasswd_path))
	{
		execute(["touch", htpasswd_path]);
	}

	bool quit_loop = false;
	while (!quit_loop)
	{
		write("Create new user (leave empty to proceed): ");
		string input = readln().strip;
		if (input == "")
		{
			quit_loop = true;
			continue;
		}

		wait(spawnProcess([
				"sudo", "htpasswd",
				htpasswd_path, input.replaceAll(regex(":"), "_")
			]));
	}
}

void main()
{
	writeln("We are going to register this site in apache2");

	auto ffmpeg_install = execute(["ffmpeg", "-version"]);
	if (ffmpeg_install.status != 0)
	{
		writeln("You don't have a valid ffmpeg installation...");
		return;
	}

	write("Select a site name: ");
	string site_name = readln().strip.replaceAll(regex(":"), "_");
	writeln("Checking wether apache2 configuration already exists...");

	int result = execute([
		"sudo", "test", "-f",
		"/etc/apache2/sites-available/" ~ site_name ~ ".conf"
	]).status;

	if (result == 0)
	{
		writeln(
			"Configuration exists, assuming it is from previous installations and not touching.");
	}
	else
	{
		writeln("Configuration does not exist. Adding new one:");
		generateConfigFile(site_name);
	}

	execute(["sudo", "a2enmod", "rewrite"]);

	handle_users();

	auto restart = execute(["sudo", "service", "apache2", "restart"]);
	if (restart.status == 0)
	{
		writeln("Everything worked fine...");
	}
	else
	{
		writeln("Couldn't restart apache2, proceeding anyway");
	}

	writeln("Cleaning up old files...");

	execute(["find", ".", "-name", "*.o", "-type", "f", "-delete"]);
	execute(["find", ".", "-name", "*.d", "-type", "f", "-delete"]);
	execute(["find", ".", "-name", "*.md", "-type", "f", "-delete"]);
	std.file.remove("install");
	std.file.remove("build");
	executeShell("find . -name '*.git*' -exec rm -rf {} +");
	std.file.remove("installer.dc");
	executeShell("sudo chown -R www-data:www-data .");

	return;
}
