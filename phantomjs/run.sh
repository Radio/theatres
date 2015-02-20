#!/bin/bash
wget -q http://theatres.kh.ua/sitemap.txt -O sitemap.txt
for URL in `cat sitemap.txt`
do
   ./make-snapshot.sh $URL
done
rm sitemap.txt