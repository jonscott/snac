#!/usr/bin/perl -w
# harvest fitness values and stuff into the appropriate table
# fitness is defined in this case by how many items are held for a 
# given search: 

use DBI;
use LWP::Simple;

$dbh = DBI->connect('DBI:mysql:autoComplete:localhost:3306',
                    'write_able_user', 'write_able_password',
                    { RaiseError => 1 }) or die "connecting : $DBI::errstr\n";

# id is the key to starting and how many lcshs you would like to look up the fitness for
$sth = $dbh->prepare("SELECT value,id FROM table_to_add_fitness_vals WHERE id > one_less_than_starting_point");
$sth->execute;

while ((@row) = $sth->fetchrow) {
#    print join(", ", map {defined $_ ? $_ : "(null)"} @row), "\n";
	$URL = "http://summit.worldcat.org/search?q=hm:\"".$row[0]."\"";
	print "$row[1] : $URL";
	$content = get($URL);
	@lines = split(/\n/,$content);
	$fitness = 0;
	foreach(@lines){
		s|.*of about <strong>(.*)</strong> \(<strong>.*$|$1| ? $fitness = $_ : next;
	}
	$fitness =~ s/,//g; 
	print " : $fitness\n";
	$sql = "UPDATE table_to_add_fitness_vals set fitness = '$fitness' where id = '$row[1]'";
	$dbh->do($sql);
}
$sth->finish;
$dbh->disconnect;
