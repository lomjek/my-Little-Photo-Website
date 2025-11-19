from PIL import Image, ExifTags, ImageOps
import argparse
from datetime import datetime
import time

log = open("Thumbnails_generation.log", "a")

parser = argparse.ArgumentParser(description="Resize and convert image to WEBP format.")
parser.add_argument('inputf', help='Input file')
parser.add_argument('outputf', help='Output file')
args = parser.parse_args()

def create(inputfile, outputfile):
    new_width = 600
    try:
        with Image.open(inputfile) as img:
            try:
                exif = img._getexif()
                if exif is not None:
                    for tag, value in exif.items():
                        decoded = ExifTags.TAGS.get(tag, tag)
                        if decoded == "Orientation":
                            log.write(str(value))
                            if value == 3:
                                img = img.rotate(180, expand=True)
                            elif value == 6:
                                img = img.rotate(270, expand=True)
                            elif value == 8:
                                img = img.rotate(90, expand=True)
                            break
            except AttributeError:
                pass

            # Calculate new dimensions preserving aspect ratio
            width, height = img.size
            new_height = int(height * new_width / width)
            img = img.resize((new_width, new_height), Image.Resampling.LANCZOS)
            img = img.convert("RGB")
            img.save(outputfile, "WEBP", quality=75)
            print("Conversion successful.")
    except Exception as e:
        print("Error: " + str(e))

log.write(f"Conversion at {datetime.fromtimestamp(time.time()).strftime('%y, %m, %d, %H:%M:%S')}: {args.inputf}, {args.outputf}\t")
create(args.inputf, args.outputf)
log.write("\n")

log.close()