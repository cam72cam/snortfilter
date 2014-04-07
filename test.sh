#/bin/bash

file="globe_data.json";
echo "[" > $file
for i in `ls geo_data`; do
	cat geo_data/$i >> $file
	echo "," >> $file
done
echo "]" >> $file
