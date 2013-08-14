<?php
/* $Id: function.inc, 03/21/2001 $ */

# spacer()
# print a IMG tag for a sized spacer GIF, white
#

function spacer($width=1, $height=1, $align=false, $extras=false) {
	printf('<img src="images_luo/spacer.gif" width="%d" height="%d" border="0" alt="" %s%s>',
		$width,
		$height,
		($align ? 'align="'.$align.'" ' : ''),
		($extras ? $extras : '')
	);
}

# shim()
# print a IMG tag for a sized spacer GIF, trans
#

function shim($width=1, $height=1, $align=false, $extras=false) {
	printf('<img src="images_luo/shim.gif" width="%d" height="%d" border="0" alt="" %s%s>',
		$width,
		$height,
		($align ? 'align="'.$align.'" ' : ''),
		($extras ? $extras : '')
	);
}


# delim()
# print a pipe delimiter
#

function delim($color=false) {
	if (!$color) {
		return '&nbsp;|&nbsp;';
	}
	return sprintf('<font color="%s">&nbsp;|&nbsp;</font>', $color );
}



# hdelim()
# print a horizontal delimiter (just a wide line);
#

function hdelim($color="#000000") {
	if (!$color) {
		return '<hr noshade size="1">';
	}
	return sprintf('<hr noshade size="1" color="%s">', $color );
}



# make_link()
# return a hyperlink to something, within the site
#

function make_link ($url, $linktext=false, $target=false, $extras=false) {
	return sprintf("<a href=\"%s\"%s%s>%s</a>",
		$url,
		($target ? ' target="'.$target.'"' : ''),
		($extras ? ' '.$extras : ''),
		($linktext ? $linktext : $url)
	);
}
# print_link()
# echo a hyperlink to something, within the site
#

function print_link($url, $linktext=false, $target=false, $extras=false) {
	echo make_link($url, $linktext, $target, $extras);
}


function make_cell ($text,$highlight=false,$link=false,$extra=false, $exbg=false) {

	$color= ($extra ? $extra : "#c1d2ff");
	$bgcolor =($exbg ? $exbg : "#efefef");
	if($highlight) {
	return sprintf("<td width=1 bgcolor=#ffffff><img width=1 height=1 ></td><td bgcolor=\"%s\" valign=\"bottom\">&nbsp;<b> %s </b>&nbsp;</td><td width=1 bgcolor=#808080><img width=1 height=1 ></td>", 
	($highlight ? $color : $bgcolor),
	$text);
	}
	else {
	return sprintf("<td width=1 bgcolor=#ffffff><img width=1 height=1 ></td><td bgcolor=\"%s\" valign=\"bottom\"><a href=\"%s\">&nbsp; %s &nbsp;</a></td><td width=1 bgcolor=#808080><img width=1 height=1 ></td>", 
	($highlight ? $color : $bgcolor),
	$link,
	$text);
	}
}
function print_cell ($text,$highlight=false,$link=false,$extra=false, $exbg=false) {
	echo make_cell($text, $highlight,$link,$extra,$exbg);
} 

function decell(){
		return '<td width=1 bgcolor=#ffffff><img width=1 height=1 alt=""></td>';
}
function decell2(){
		return '<td width=1 bgcolor=#808080><img width=1 height=1 alt=""></td>';
}
function print_email($email){
       echo make_email($email);
}

function make_email($email){
	return sprintf(' <a href="mailto:%s">%s</a>',$email,$email);
}

# print_decell()
# echo a hyperlink to something, within the site
#


function print_decell()
{
	echo "<td bgcolor=#ffffff><img width=10 height=1></td>";
}


function print_tab($text, $gif, $highlight=false,$link=false,$extra=false, $exbg=false) {
	echo make_tab($text,$gif, $highlight,$link,$extra,$exbg);
} 

# make_tab()
# echo a hyperlink to something, within the site
#

function make_tab($text,$gif,$highlight=false,$link=false,$extra=false, $exbg=false) {

	$on='_32_on.gif';
	$off='_24_on.gif';
	$color= ($extra ? $extra : "#c1d2ff");
	$bgcolor =($exbg ? $exbg : "#efefef");
	if($highlight) {
	$icon="images_luo/$gif$on";
	return sprintf("<table bgcolor=\"%s\" border=0 cellspacing=0 cellpadding=0><tr><td colspan=3 bgcolor=\"%s\"><img width=1 height=1></td></tr><tr><td  width=1 bgcolor=#efefef><img width=1 height=1></td><td><img src=\"%s\" border=0><font color='#ffffff'><b> %s </b></font></td><td width=1 bgcolor=#808080><img width=1 height=1></td></tr></table>", 
	$color, 
	$color, 
	$icon, 
	$text);
	}
	else {
	$icon="images_luo/$gif$off";
	return sprintf("<table bgcolor=\"%s\" border=0 cellspacing=0 cellpadding=0><tr><td colspan=3 bgcolor=#dfdfdf><img width=1 height=1></td></tr><tr><td  width=1 bgcolor=#dfdfdf><img width=1 height=1></td><td><a href=\"%s\" target=\"_top\"><img src=\"%s\" border=0><b> %s </b></a></td><td width=1 bgcolor=#dfdfdf><img width=1 height=1 ></td></tr></table>", 
	$bgcolor,
	$link, 
	$icon, 
	$text);
	}
}

function XMLFormdata( $saFormdata, $iLevel = 1 ) {
	$result = (($iLevel==1)?"<formdata>\n":"");
	while( list( $sElement, $sValue ) = each( $saFormdata ) ) {
		$result .= str_repeat("\t",$iLevel)."<".((is_int($sElement))?"count":"").$sElement.">";
		if( is_array($sValue) ) {
			$result .=  "\n";
			$result .= XMLFormdata( $sValue, $iLevel + 1 );
			$result .= str_repeat("\t",$iLevel);
		} else {
			$result .= $sValue;
		} // if/else
		$result .= "</".((is_int($sElement))?"count":"").$sElement.">\n";
	} // while
	$result .= (($iLevel==1)?"</formdata>\n":"");
	return $result;
} // function XMLFormdata




?>
	
