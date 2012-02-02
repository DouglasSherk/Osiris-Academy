rm package/*
for TYPE in `ls`; do
    if [ ! -d "$TYPE" ] || [ "$TYPE" == "files.php" ] || [ "$TYPE" == "package.sh" ] || [ "$TYPE" == "package" ]; then
        continue
    fi
    for TERM in `ls $TYPE`; do
        zip -r -g "package/$TERM.zip" "$TYPE/$TERM/"
    done
done
