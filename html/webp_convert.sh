# Check if cwebp is installed
if ! command -v cwebp &> /dev/null; then
    echo "cwebp is not installed. Please install libwebp-tools."
    exit 1
fi

# Check if gif2webp is installed
if ! command -v gif2webp &> /dev/null; then
    echo "gif2webp is not installed. Please install libwebp-tools."
    exit 1
fi

# Folder path provided as command-line argument
FOLDER_PATH_IMAGES="public/test3/uploads"
FOLDER_PATH_MD="public/test3/articles"

# Check if the specified folder exists
if [ ! -d "$FOLDER_PATH_IMAGES" ]; then
    echo "Folder '$FOLDER_PATH_IMAGES' does not exist."
    exit 1
fi

# Create output directory if it doesn't exist
OUTPUT_DIR="$FOLDER_PATH_IMAGES"

# Convert jpg and jpeg images to webp
for file in "$FOLDER_PATH_IMAGES"/*.jpg "$FOLDER_PATH_IMAGES"/*.jpeg "$FOLDER_PATH_IMAGES"/*.png; do
    if [ -f "$file" ]; then
        filename=$(basename -- "$file")
        filename="${filename%.*}"
        cwebp -q 80 "$file" -o "$OUTPUT_DIR/$filename.webp"
    fi
done

# Convert gif images to webp
for file in "$FOLDER_PATH_IMAGES"/*.gif; do
    if [ -f "$file" ]; then
        filename=$(basename -- "$file")
        filename="${filename%.*}"
        gif2webp "$file" -o "$OUTPUT_DIR/$filename.webp"
    fi
done

echo "Image conversion completed. WebP images are saved in '$OUTPUT_DIR' directory."

# Replace image extensions in .md files
for mdfile in "$FOLDER_PATH_MD"/*.md; do
    if [ -f "$mdfile" ]; then
        sed -i'.bak' -E 's/(\.jpg|\.jpeg|\.gif|\.png)/.webp/g' "$mdfile"
        rm "$mdfile.bak"
    fi
done

echo "Extension replacement completed in .md files."
