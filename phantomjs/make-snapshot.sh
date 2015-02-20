U=$1
P=$( echo "$U" | perl -MURI -le 'chomp($url = <>); print URI->new($url)->fragment' )
final_url=$U
P=$(echo ${P:1} | tr / -)
final_path=../web/snapshots/snap$P.html
/opt/phantomjs/bin/phantomjs .phantomjs-runner.js $final_url > $final_path
