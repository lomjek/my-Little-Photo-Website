/*******************************************************/
/*   This file is part of 'my Little Photo Website'    */
/* It is published on github under the LGPLv3 License: */
/*  https://github.com/lomjek/my-Little-Photo-Website  */
/*******************************************************/

module image_processing;

import std;
import libs.root_conf;

struct img_size
{
    uint x, y;
}

img_size[double] aspect_ratios;
double[7] aspect_ratio_index;

img_size get_thumb_res(img_size original_size)
{
    double original_aspect_ratio = cast(double) original_size.x / original_size.y;
    writeln(original_aspect_ratio);
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

    //Get the resolution
    writeln(best_aspect_ratio);

    double[3] high_formats = [1405.0 / 525, 2110.0 / 525, 2815.0 / 525];
    double[3] wide_formats = [700.0 / 1055, 700.0 / 1585, 700.0 / 2115];

    //TODO: Figure out *contains* function
    if (high_formats[0] == best_aspect_ratio || high_formats[1] == best_aspect_ratio || high_formats[2] == best_aspect_ratio)
    { //For high images
        if (original_size.y >= aspect_ratios[best_aspect_ratio].y)
        {
            return aspect_ratios[best_aspect_ratio];
        }
        else
        {
            return img_size(cast(uint)(original_size.y * best_aspect_ratio), original_size.y);
        }
    }

    if (wide_formats[0] == best_aspect_ratio || wide_formats[1] == best_aspect_ratio || wide_formats[2] == best_aspect_ratio)
    { //For high images
        if (original_size.x >= aspect_ratios[best_aspect_ratio].x)
        {
            return aspect_ratios[best_aspect_ratio];
        }
        else
        {
            return img_size(original_size.x, cast(uint)(original_size.x / best_aspect_ratio));
        }
    }

    if (original_size.x > 700 && original_size.y > 525)
    {
        return img_size(700, 525);
    }

    if (original_aspect_ratio > 700.0 / 525)
    {
        return img_size(cast(uint)(original_size.y * best_aspect_ratio), original_size.y);
    }
    else if (original_aspect_ratio < 700.0 / 525)
    {
        return img_size(original_size.x, cast(uint)(original_size.x / best_aspect_ratio));
    }

    return aspect_ratios[700.0 / 525];
}

img_size get_original_res(string path)
{
    string command = escapeShellCommand([
        "ffprobe",
        "-v", "error", //Only print errors, if they happened...
        "-select_streams", "v:0",
        "-show_entries", "stream=width,height",
        "-of", "csv=s=x:p=0",
        path
    ]);

    auto ffmpeg = executeShell(command);

    if (ffmpeg.status != 0)
    {
        writeln("Something went wrong");
        return img_size(0, 0);
    }
    string result = strip(ffmpeg.output);
    return img_size(result.split("x")[0].to!uint, cast(uint) result.split("x")[1].to!uint);
}

void main()
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

    writeln(aspect_ratio_index);
    writeln(get_original_res(buildPath(root_path, "photos", "Geocache", "example.webp")));
    writeln(get_thumb_res(img_size(1282, 3168)));
}
