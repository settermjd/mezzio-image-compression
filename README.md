# Mezzio Image Compression

This is a small project showing how to do simplistic image compression in PHP.

## Usage

First, install the project, by running the following command.

```bash
git clone git@github.com:settermjd/mezzio-image-compression.git
```

Then, start the application by running the following command.

```bash
composer serve
```

Then, upload and compress an image by replacing `<<Upload Image>>` and `<<Uploaded File Name>>` with the path to an image that you want to upload and a name for the file after being uploaded respective, in the command below, before running it.

```bash
curl --silent \
    -F image=@<<Upload Image>> http://localhost:8080 \
    -F file_name="<<Uploaded File Name>>" \
    -F image_quality="20" | jq
```

You should see output similar to the example below.

```bash
{
    "original file": {
        "File name": "/var/www/html/data/uploads/Audacity.png",
        "File size": 89742,
        "File type": "image/png"
    },
    "new file": {
        "File name": "/var/www/html/data/uploads/Audacity.jpg",
        "File size": 35958,
        "File type": "image/jpeg"
    }
}
```