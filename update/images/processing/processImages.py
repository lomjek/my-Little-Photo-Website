from PIL import Image, ExifTags
import os
from datetime import datetime

root = os.path.abspath(os.path.dirname(__file__) + "/../../../")
tmp_dir = os.path.join(root, "update/images/uploads/")
final_dir = os.path.join(root, "photos/")

lockfile = os.path.join(os.path.dirname(__file__), "process.lock")
if os.path.exists(lockfile):
    exit(0)
else:
    file = open(lockfile, "w")
    file.close()

def log(text):
    now = datetime.now()
    with open(os.path.dirname(__file__) + "/conversion.log", "a") as file:
        file.write(now.strftime("%Y-%m-%d %H:%M:%S") + ": " + text + "\n")
    print(text)

def convert_to_webp(input_path, output_path):
    try:
        with Image.open(input_path) as img:  # Open the input image
            exif_data = img._getexif()
            orientation = None
            if exif_data: # Get the orientation tag
                for tag, value in exif_data.items():
                    tag_name = ExifTags.TAGS.get(tag, tag)
                    if tag_name == 'Orientation':
                        orientation = value
                        break
            if orientation is not None: # Rotate the image based on the orientation
                if orientation == 3:
                    img = img.rotate(180, expand=True)
                elif orientation == 6:
                    img = img.rotate(270, expand=True)
                elif orientation == 8:
                    img = img.rotate(90, expand=True)
            img.save(output_path, format='WEBP', quality=95)  # Convert and save the image in WebP format with specified quality
        log(f"Image successfully converted to {output_path}")
    except Exception as e:
        log(f"An error occurred: {e}, when converting {input_path}")

def create_Thumbnail(input_path, output_path):
    try:
        with Image.open(input_path) as img:  # Open the input image
            width_percent = (1000 / float(img.size[0]))
            new_height = int((float(img.size[1]) * float(width_percent)))
            exif_data = img._getexif()
            orientation = None
            if exif_data: # Get the orientation tag
                for tag, value in exif_data.items():
                    tag_name = ExifTags.TAGS.get(tag, tag)
                    if tag_name == 'Orientation':
                        orientation = value
                        break
            if orientation is not None: # Rotate the image based on the orientation
                if orientation == 3:
                    img = img.rotate(180, expand=True)
                elif orientation == 6:
                    img = img.rotate(270, expand=True)
                elif orientation == 8:
                    img = img.rotate(90, expand=True)
            img.thumbnail((1000, new_height))
            img.save(output_path, format='WEBP', quality=85)  # Convert and save the image in WebP format with specified quality
        log(f"Thumbnail succesfully created: {output_path}")
    except Exception as e:
        log(f"An error occurred: {e}, when thumbnailing {input_path}")

dirs = [f for f in os.listdir(tmp_dir) if os.path.isdir(os.path.join(tmp_dir, f))]
for collection in dirs:
    collection_dir = os.path.join(tmp_dir, collection)
    files = [f for f in os.listdir(collection_dir) if os.path.isfile(os.path.join(collection_dir, f))]
    for file in files:
        file_path = os.path.join(collection_dir, file)
        basename = file.split(".")[0].replace(" ", "_")
        final_path = os.path.join(final_dir, collection, basename + ".webp")
        counter = 1
        while os.path.exists(final_path):
            final_path = os.path.join(final_dir, collection, basename + "_" + str(counter) + ".webp")
            counter += 1       
        convert_to_webp(file_path, final_path)
        
        directory, filename = os.path.split(final_path)
        thmb_path = os.path.join(directory, ".t_" + filename)
        create_Thumbnail(file_path, thmb_path)
        os.remove(file_path)

os.remove(lockfile)
