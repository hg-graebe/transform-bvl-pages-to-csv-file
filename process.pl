undef $/;
open(FH,"adressen.nt") or die;
$_=<FH>;
close FH;
s|<([^>]+)> <http://le-online.de/ontology/place/ns#fixedAddress> \"([^\"]+)\"\^\^<http://www.w3.org/2001/XMLSchema#string> .\n|process($1,$2)|egs;
print $_;

## end main ##

sub process {
  # print "start process";
  my ($subject,$content)=@_;
  $content=~m|(.+)\s+([\d-\w]+),\s+(\d+)\s+(.+)|;
  my $strasse=$1;
  my $nr=$2;
  my $plz=$3;
  my $ort=$4;
  my $id=fixId("$plz.$ort.$strasse.$nr");
  return <<EOT;
<$subject> <http://le-online.de/ontology/place/ns#fixedAddress> "$content"^^<http://www.w3.org/2001/XMLSchema#string> .
<$subject> <http://le-online.de/ontology/place/ns#ldAddress> <http://leipzig-data.de/Data/$id> .
EOT
}

sub fixId {
  local $_=shift;
  s/\s+//g;
  s/\\u00E4/ae/g;
  s/\\u00F6/oe/g;
  s/\\u00FC/ue/g;
  s/\\u00DF/ss/g;
  s/\\u00E9/e/g;
  return $_;
}
