from PIL import Image, ExifTags, ImageOps
import argparse

parser = argparse.ArgumentParser(description="argparse")
parser.add_argument('inputf', help='Imput file')
parser.add_argument('outputf', help='Output file')
args = parser.parse_args()

def create(inputfile, outputfile):
    newidth = 600
    try:
        with Image.open(inputfile) as img:
            width, height = img.size
            new_height = int(height * newidth / width)
            img = ImageOps.exif_transpose(img)
            img = img.resize((newidth, new_height))
            img = img.convert("RGB")
            img.save(outputfile, "WEBP", quality=75)
            print("Conversion succesfull.")
    except Exception as e:
        print("Error: " + str(e))

create(args.inputf, args.outputf)
