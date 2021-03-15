<?php
$x = 0;
$imageSize = 123;
$imageCount = 4000;
$maxFiles = 30;
$sleep = 60;
$checked = "checked";
$ignore = '9gag|youtube|reddit';
if ($x == 0) {
	$folder = "/capture/images";
	$settingsFile = "/capture/options";

	$imageCount = iterator_count(new FilesystemIterator($folder, FilesystemIterator::SKIP_DOTS));

	$io = popen('/usr/bin/du -sm ' . $folder, 'r');
	$size = fgets($io, 4096);
	$imageSize  = substr($size, 0, strpos($size, "\t"));
	pclose($io);

	$handle = fopen($settingsFile, "r");
	if ($handle) {
		while (($line = fgets($handle)) !== false) {
			$setting = explode("=", $line);
			switch ($setting[0]) {
				case "SLEEP":
					$sleep = trim($setting[1]);
					break;
				case "MAXFILES":
					$maxFiles = trim($setting[1]);
					break;
				case "IGNORE":
					$ignore = str_replace('\\\|', "\n", trim($setting[1]," \t\n\r\0\x0B\""));
					break;
				case "INDEX":
					$checked = trim($setting[1]) == "true" ? "checked" : "";
					break;
				default:
			}
		}
		fclose($handle);
	} else {
		// error opening the file.
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<header>
	<title>Superbusca</title>
	<link href="//cdn.jsdelivr.net/npm/featherlight@1.7.14/release/featherlight.min.css" type="text/css" rel="stylesheet" />
	<script src="//code.jquery.com/jquery-latest.js"></script>
	<script src="//cdn.jsdelivr.net/npm/featherlight@1.7.14/release/featherlight.min.js" type="text/javascript" charset="utf-8"></script>
	<link rel="stylesheet" href="style.css" />
	<link rel="stylesheet" href="switch.css" />
	<script type="text/javascript" src="search.js">
	</script>
</header>

<body>
	<div class="clear">
		<form onsubmit="event.preventDefault();" class="searchForm">
			Search: <input type='text' autocomplete="off" id='searchPhrase' name='search' />
			<input type='button' value='OK' onclick="javascript:doSearch()" />
		</form>
		<div class="options">
			Indexing <?php echo $imageCount ?> images (<?php echo $imageSize ?> MB).
			<ul>
				<li class="middle"><a href="#" data-featherlight="#config">Settings</a></li>
				<li class="middle">Link 2</li>
				<li class="middle">
					<span>
						Capture:
						<label class="switch">
							<input name="INDEX" id="INDEX" type="checkbox" <?php echo $checked ?> onclick="javascript:changeSetting(this)" class='bool'>
							<span class="slider round"></span>
						</label>
					</span>
				</li>
			</ul>
		</div>
	</div>
	<br class="clear" />
	<hr />
	<p>Results</p>
	<div id='searchResult'>&nbsp;</div>
	<div id="config" class="hide">
		<form>
			<fieldset>
				<legend>Settings</legend>
				<div class='setting'>
					<label for='MAXFILES'>Index every</label>
					<input title="Max number of files to wait before indexing." id="MAXFILES" name="MAXFILES" type="text" size="2" class="int" value="<?php echo $maxFiles ?>" onblur='javacript:changeSetting(this)' /> files
				</div>
				<div class='setting'>
					<label for='SLEEP'>Sleep:</label>
					<input id="SLEEP" name="SLEEP" title="Time in seconds to wait between captures." type="text" size="2" class="int" value="<?php echo $sleep ?>" onblur='javacript:changeSetting(this)' /> seconds<br />
				</div>
				<div class='clear'>
					<div class='clear middle'>
						<label for='IGNORE'>Ignore:</label>
						<textarea name='IGNORE' id='IGNORE' class='text' cols=30 rows=6 title="If the title of the active window is in the list (one per line), don't capture." onblur='javacript:changeSetting(this)' ><?php echo $ignore ?></textarea><br>
						One item per line.
					</div>
				</div>
			</fieldset>
		</form>
	</div>
	<script>
		document.getElementById('searchPhrase').addEventListener('keypress', function(e) {
			if (e.keyCode == 13) {
				doSearch();
			}
		}, false);
	</script>
</body>

</html>