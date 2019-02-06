<?php
/**
* This file is intended to make a complete RTF data structure compliant file from a text file
*
* @parameter 
* @author Craig Duncan
* @updated 26 March 2015
*
* Functions that require use of global variables must use the global keyword to declare/pass them within the function.
*/

echo "<p>Hello there </p>";
$endBrace="}";
$return=PHP_EOL;

/**
* This function will read in the filename $d passed as an argument to the function (including extension)
* It reads in the buffer size of 8192 bytes, then adds the file to the main variable '$RTFdata'
*/

function readMyFile($d)
{
global $lines, $dateTime,$RTFdata;
$dateTime=date('l jS \of F Y h:i:s A');
$buffer="";//clear buffer between file reads
$R=PHP_EOL;
//$d = "sampleclause.txt";
echo "<p>".$dateTime.",".$d."</p>";
$fd = fopen($d, 'r') or die("can't open file"); 
//$fd = fopen($dataFile, 'r') or die("can't open filey,".$dateTime); 
echo "<p> Loop starts now</p>";
while (!feof ($fd))
{
   $buffer = fgets($fd, 8192);//max size of read
   //echo ftell($fd); 
   //echo ",";
   //addRTFpara($buffer,2); //each new line in text file is written as RTF para with header style 2 - use this for plain text no heading codes so that it inserts correct end of para codes
   //use thie addString if addRTFpara not used:
   $RTFdata=$RTFdata.$R.$buffer;
}
echo "<p> Loop ends now</p>";
fclose ($fd);
return;
}
function addString ($I) //This function adds a string to the default data String ($RTFdata)
{
global $RTFdata;
$R=PHP_EOL;
$RTFdata=$RTFdata.$R.$I;
return;
}

/**
* Each RTF document needs an introductory section, but it also needs matching braces {} - the first empty brace will be closed
* in the last function call

* @parameter for basicRTF: begin rtf file group, ansi format, default font is set to f0 in font table, 
* default format to save is RTF, doc type general, page layout view (0 = none)
*
* @parameter for *fontTable = rows for each font f1, f2, f3, f4 etc
*
* @paramater for colourTable = RGB
*/


function makeRTFstart()
{
global $RTFdata,$basicRTF,$fontTable,$colTable;
$basicRTF='{\rtf1\ansi\deff3\adeflang1025\defformat\doctype0\viewkind1'; 
$fontTable='{\fonttbl{\f0 \fprq2 Garamond;}{\f1 \froman \fprq2 Lucida Sans;}{\f2\fswiss\fprq2\fcharset128 Arial;}{\f3 Cambria;}{\f4\fnil\fprq2\fcharset128 Arial Unicode MS;}{\f5\fnil\fprq2\fcharset128 MS Mincho;}{\f6\fnil\fprq2\fcharset128 Tahoma;}{\f7\fnil\fprq0\fcharset128 Tahoma;}{\f8\fprq0\fcharset128 Courier New;}}'; 
$colTable='{\colortbl;\red0\green0\blue0;\red128\green128\blue128;}';
$RTFdata=$basicRTF;
 addString ($fontTable);
 addString ($colTable);
 return $RTFdata;
}

/**
*
* The definition of a style as an outline style involves multiple parameters, for outlining, indenting
* This is then embedded inside the styles that are contained in the style table.
* But it is also repeated inside every paragraph to which that style is applied.
* This is because the style table functions simply as a library of what a style is, but when Word applies a style it
* copies it into the relevant paragraph.  A mere reference to a style in a paragraph is used by Word's style inspector, but not for
* rendering the individual parameters in that paragraph.  The paragraph environment is the one in which the parameters must be present.
*
* 
*
*/

function SetRTFstyles ()
{ 
global $IndentStyle,$BaseStyle,$H1style, $H2style, $H3style, $H4style;
$BaseStyle='\sa200\cf0{\*\hyphen2\hyphlead2\hyphtrail2\hyphmax0}\aspalpha\rtlch\af3\afs24\lang3081\ltrch\dbch\af3\langfe1033\hich\f1\fs24\lang3081\lochlang3081';
$H1style='\outlinelevel0\ilvl0\ls1\li709\ri0\lin709\rin0\fi-709\sb360\brdrb\brdrs\brdrw1\brdrcf1\brsp60{\*\brdrb\brdlncol1\brdlnin0\brdlnout1\brdlndist0}\brsp60\keepn\cf0\sl312\slmult1\tx709{\*\hyphen2\hyphlead2\hyphtrail2\hyphmax0}\aspalpha\rtlch\af3\afs24\lang3081\ab\ltrch\dbch\af3\langfe1033\hich\f3\fs24\lang3081\loch\lang3081\b ';//you need a space after the \b for bold codeword
$H2style='\ql \fi-709\li709\ri0\sb200\sl312\slmult1\nowidctlpar
\tx709\wrapdefault\aspalpha\faauto\ls1\ilvl1\outlinelevel1\rin0\lin709\itap0\pararsid6228519 \rtlch\fcs1 \af3\afs24\alang3081 \ltrch\fcs0 \f3\fs24\lang3081\langfe1033\cgrid\langnp3081\langfenp1033'; //this is all the paragraph format info we need - but it relies on list override 1 to link it to level 2
$H3style="\ls1\ilvl2\outlinelevel2\li1418\\ri0\lin1418\\rin0\\fi-709\sb100\cf0\sl312\slmult1\\tx1418{\*\hyphen2\hyphlead2\hyphtrail2\hyphmax0}\aspalpha\\rtlch\af3\afs24\lang3081\ltrch\dbch\af3\langfe1033\hich\\f6\\fs24\lang3081\loch\\f3\\fs24\lang3081";
$H4style="\ls1\ilvl3\outlinelevel3\li2126\\ri0\lin2126\\rin0\\fi-708\sb100\cf0\sl312\slmult1\\tx2126{\*\hyphen2\hyphlead2\hyphtrail2\hyphmax0}\aspalpha\\rtlch\af3\afs24\lang3081\ltrch\dbch\af3\langfe1033\hich\\f3\\fs24\lang3081\loch\\f3\\fs24\lang3081";//change indent level to 3 ilvl3.  This aligns the heading 4 outline number level to the correct outline level
/*$IndentStyle='\ql \ri0\sb200\sl312\slmult1\nowidctlpar
\tx709\wrapdefault\aspalpha\faauto\rin0\lin709\itap0 \rtlch\fcs1 \af3\afs24\alang3081 \ltrch\fcs0 \f1\fs24\lang3081\langfe1033'; 
this should match H2 except without numbers, no outline level, no indent level, no li1418
*/
$IndentStyle='\ql \li0\ri0\sb200\sl312\slmult1\nowidctlpar\tx709\wrapdefault\aspalpha\faauto\rin0\lin0\itap0 \rtlch\fcs1 \af23\afs24\alang3081 \ltrch\fcs0 \b\f3\fs24\lang3081\langfe1033\cgrid\langnp3081\langfenp1033';
$T1Style='\ql \li0\ri0\trkeep\trftsWidthB3\trpaddl108\trpaddr108\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3\tblind0\tblindtype3\tscellwidthfts0\tsvertalt\tsbrdrt\tsbrdrl\tsbrdrb\tsbrdrr\tsbrdrdgl\tsbrdrdgr\tsbrdrh\tsbrdrv \widctlpar\wrapdefault\aspalpha\aspnum\faauto\adjustright\rin0\lin0\itap0 \rtlch\fcs1 \af0\afs20\alang1033 \ltrch\fcs0 \fs20\lang1033\langfe1033\cgrid\langnp1033\langfenp1033'; //left indent 1418  removed \lin0
}

/**
* A style sheet is a section of an RTF document that lists the font information that is associated with a style.
* 
* not all style sheet entries are for paragraphs - if they are then include a \sN word at beginning, where N is a number
*
*  I think there is a distinction between when the font/para information is needed and how it is used, depending on context.
* Two different contexts: what is shown in the WYWIWYG output; and what the style inspector identifies and displays
*
*  If a paragraph section of an RTF document were to be written without font information, then Word can only show 
*  the default font (\deffN) with it's WYSIWYG output 
*   However, if that same paragraph contains a style definition, Word's style inspector will (a) list that style 
* (b) make a comparison to the default font used by the WYSIWYG editor i.e. ignore any common characteristics and identify where the default font differs from 
* the nominated style e.g. \s12 etc, then (d) in the style inspector, show how the style is effectively modified by the default font.  
*
*  The style sheet must specify the style of the paragraph to follow, so that the WYSIWYG and the style inspector can make use of it.
*
* Each line of the style sheet entry is enclosed by braces {}, but it must also conclude by a semi-colon before the brace
*
*/

function makeStyleSheet ()
{
global $IndentStyle,$BaseStyle,$H1style, $H2style, $H3style, $H4style, $T1Style,$StyleTable, $endBrace;
SetRTFstyles();
SetRTFparas();
$SF=array();
// or try $SF= new SplFixedArray(24);
$SF[0]="{\stylesheet";
$SF[1]='{\s1'.$BaseStyle.'\snext1 Base;}';
$SF[2]="{\s2\sb240\sa120\keepn\cf0{\*\hyphen2\hyphlead2\hyphtrail2\hyphmax0}\aspalpha\\rtlch\af6\afs28\lang3081\ltrch\dbch\af5\langfe1033\hich\\f2\\fs28\lang3081\loch\\f2\\fs28\lang3081\sbasedon1\snext3 Heading;}";
$SF[3]="{\s3\sa120\cf0{\*\hyphen2\hyphlead2\hyphtrail2\hyphmax0}\aspalpha\\rtlch\af3\afs24\lang3081\ltrch\dbch\af3\langfe1033\hich\\f3\\fs24\lang3081\loch\\f3\\fs24\lang3081\sbasedon1\snext3 Body Text;}";
$SF[4]="{\s4\sa120\cf0{\*\hyphen2\hyphlead2\hyphtrail2\hyphmax0}\aspalpha\\rtlch\af7\afs24\lang3081\ltrch\dbch\af3\langfe1033\hich\\f3\\fs24\lang3081\loch\\f3\\fs24\lang3081\sbasedon3\snext4 List;}";
$SF[5]="{\s5\sb120\sa120\cf0{\*\hyphen2\hyphlead2\hyphtrail2\hyphmax0}\aspalpha\\rtlch\af7\afs24\lang3081\ai\ltrch\dbch\af3\langfe1033\hich\\f3\\fs24\lang3081\i\loch\\f3\\fs24\lang3081\i\sbasedon1\snext5 caption;}";
$SF[6]="{\s6\sa200\cf0{\*\hyphen2\hyphlead2\hyphtrail2\hyphmax0}\aspalpha\\rtlch\af7\afs24\lang3081\ltrch\dbch\af3\langfe1033\hich\\f3\\fs24\lang3081\loch\\f3\\fs24\lang3081\sbasedon1\snext6 Index;}";
$SF[7]="{\s7\sb100\cf0\sl312\slmult1{\*\hyphen2\hyphlead2\hyphtrail2\hyphmax0}\aspalpha\\rtlch\af3\afs24\lang3081\ltrch\dbch\af3\langfe1033\hich\\f3\\fs24\lang3081\loch\\f3\\fs24\lang3081\sbasedon1\snext7  Heading 6;}";
$SF[8]="{\s8\sb100\cf0\sl312\slmult1{\*\hyphen2\hyphlead2\hyphtrail2\hyphmax0}\aspalpha\\rtlch\af3\afs24\lang3081\ltrch\dbch\af3\langfe1033\hich\\f3\\fs24\lang3081\loch\\f3\\fs24\lang3081\sbasedon1\snext8 Heading 5;}";
//This style will be linked to Heading 4 paragraph style
$SF[9]="{\s9".$H4style."\sbasedon1\snext12 Heading 4;}";
//This style will be linked to Heading 3 paragraph 
$SF[10]="{\s10".$H3style."\sbasedon1\snext11 Heading 3;}";
$SF[11]="{\s11".$H2style."\sbasedon1\snext10  Heading 2;}"; //name the style, add the paragraph info, then the based on, next and name 

$SF[12]="{\s12".$H1style."\sbasedon1\snext11 Heading 1;}"; //next para style will be H2
//table style default
$SF[13]="{\s25".$IndentStyle."\sbasedon1\snext1  Indent;}"; //name the style, add the paragraph info, then the based on, next and name 
$SF[14]='{\ts13\tsrowd'.$T1Style.' \snext13 Table;}';
//These are character styles in a table that can be applied as and when needed
//$SFstring="\cf0\\rtlch\af3\afs24\lang1033\ltrch\dbch\af4\langfe1033\hich\lang1033\loch\\f0\\fs24\lang1033 ";
$SFstring="\cf0\\rtlch\af3\afs24\lang1033\ltrch\dbch\af4\langfe1033\hich\lang1033\loch\lang1033 ";
$SF[15]='{\*\cs14'.$SFstring.'\f0\fs36\ CSHeading 2 1'.';}';
$SF[16]="{\*\cs15".$SFstring."\\f1\\fs24\ CSHeading 2 2".";}";
$SF[17]="{\*\cs16".$SFstring."\\f2\\fs30\ CSHeading 2 3".";}";
$SF[18]="{\*\cs17".$SFstring."\\f3\\fs28\ CSHeading 2 4".";}";
$SF[19]="{\*\cs18".$SFstring."\\f0\\fs36\ CSHeading 2 5".";}";
$SF[20]="{\*\cs19".$SFstring."\\f0\\fs36\ CSHeading 2 6".";}";
$SF[21]="{\*\cs20".$SFstring."\\f0\\fs36\ CSHeading 2 7".";}";
$SF[22]="{\*\cs21".$SFstring."\\f0\\fs36\ CSHeading 2 8".";}";
$SF[23]="{\*\cs22".$SFstring."\\f0\\fs36\ CSHeading 2 9".";}";
$SF[24]="{\*\cs23".$SFstring."\\f0\\fs36\Default Paragraph Font".";}";
//var_dump($SF);
for ($st=0;$st<25; $st++) 
{
$StyleTable=$StyleTable.$SF[$st];
}
$StyleTable=$StyleTable.$endBrace;
addString($StyleTable);
return;
}

/**
* -------------------LIST TABLE DATA FORMAT-----------------------
*
*  The List table is a list which specifies the NUMBERING to follow.  
* The style information in the 'listlevel' section is font/text style for the numbers that are used (not the paragraph itself)
* The list template can be given a name e.g. 'listid1' is used as a unique reference, but it can be anything.
*
* There seems to be an archaic cross-referencing system: you need to specify a list-override (in a list-override table) to allow a list to have formatting
* that is associated with the style, and not just the default list numbering font.
* Word used to specify list hybrid, but it is now a simpler system (post Word 2007).
*  Now the list override table just specifies a lookup id to a list, with an overridecount of 0.
* This lookup id enables you to override the list you have just created in the list-table (e.g. 'listid1')
* post Word 2007, if you want a list override that is just a lookup id to a list, do NOT include 'listhybrid' tag; also  make the overridecount 0
*  

*/


/**
* To make an RTF list table, 
* The list table defines the numbering system, and the numbers to follow at the next level in the list
* for hybrid lists the {leveltext}} word must also include leveltemplateID as first argument & unique to each of 9 levels: RTf spec1.9.1
*
* levelnfc0 means Arabic numbers to be used; levelljc0 means left justified; levelstartat1 for this level
* Each list level can contain any character properties which will affect all number text for the levels in the list;   
* paragraph properties for list level definitions are restricted to the jclisttab - 
* any combination of left indents, first line left indents and tabs
*
* Each listlevel specification line ends in a /sN specification for a style
* whatever specified /sN appears before the brace is the style associated with this level in the list
* You have to use double quotes here to store the strings because single quotes are part of the list level formatting 
* Since some of the formatting codes require characters that would be recognised differently in double quote, you escape out \\f etc
*
* list level 1 should be heading 1 - top level of list (I'm including a bold code \b here as default - that makes the number text bold)
*
*/

function makeListTable ()
{
$listTable="{\*\listtable{\list\listtemplateid1\listid1"; 
$level=array(); 
$level[1]="{\listlevel\levelnfc0\leveljc0\levelstartat1\levelfollow0{\leveltext \leveltemplateid21 \'02\'00.;}{\levelnumbers\'01;}\b\\f3\\fs24\\fi-709\li709\s12}"; 
$level[2]="{\listlevel\levelnfc0\leveljc0\levelstartat1\levelfollow0{\leveltext \leveltemplateid22 \'03\'00.\'01;}{\levelnumbers\'01\'03;}\\f3\\fi-709\li709\s11}";
$level[3]="{\listlevel\levelnfc4\leveljc0\levelstartat1\levelfollow0{\leveltext \leveltemplateid23 \'03(\'02);}{\levelnumbers\'02;}\\f3\\fi-709\li1418\s10}";
$level[4]="{\listlevel\levelnfc2\leveljc0\levelstartat1\levelfollow0{\leveltext \leveltemplateid24 \'03(\'03);}{\levelnumbers\'02;}\\f3\\fi-708\li2126\s9}";
$level[5]="{\listlevel\levelnfc3\leveljc0\levelstartat1\levelfollow0{\leveltext \leveltemplateid25 \'03(\'04);}{\levelnumbers\'02;}\\f3\\fi-709\li2835}";
$level[6]="{\listlevel\levelnfc0\leveljc0\levelstartat1\levelfollow0{\leveltext \leveltemplateid26 \'03(\'05);}{\levelnumbers\'02;}\\f3\\fi-709\li3544}";
$level[7]="{\listlevel\levelnfc0\leveljc0\levelstartat1\levelfollow0{\leveltext \leveltemplateid27 \'02\'06.;}{\levelnumbers\'01;}\\f3\\fi-709\li709}";
$level[8]="{\listlevel\levelnfc4\leveljc0\levelstartat1\levelfollow0{\leveltext \leveltemplateid28 \'03(\'07);}{\levelnumbers\'02;}\\f3\\fi-709\li1418}";
$level[9]="{\listlevel\levelnfc2\leveljc0\levelstartat1\levelfollow0{\leveltext \leveltemplateid29 \'03(\'08);}{\levelnumbers\'02;}\\f3\\fi-708\li2126}";
$listEnd="{\listname Headings2;}}}";
for ($lt=1;$lt<10;$lt++) 
{
$listTable=$listTable.$level[$lt];
}
$listTable=$listTable.$listEnd;
addString($listTable);
}

/**
* 
* A function to make a list override table
* A list 
* 
* The list override table is important because a formatting override allows a paragraph to be part of a list and to be numbered along 
* with the other members of the list, but to have different formatting properties
* This particular list override overrides 'listid1' list in list table but does nothing; this override index is ls1 
* It preserves the outline numbered list with 9 levels defined by listid1 (due to listoverridecount0?)
*
*/

function makeListOR()
{
	global $listOR2;
	$listOR2="{\*\listoverridetable{\listoverride\listid1\listoverridecount0\ls1}}"; 
	addString($listOR2);
}

/**
* -------------------INFORMATION (PROPERTIES) TABLE DATA FORMAT: STD ENTRY -----------------------
*
*/

function makeInfoTable()
{
$infoTable="{\info{\operator Craig Duncan}{\author Craig Duncan}{\\title This is an RTF document}{\keywords RTF autogenerate}{\doccomm (c) 2014 Craig Duncan}{\subject Document Assembly}{\company Craig Duncan, Commercial Legal Services}{\creatim\yr2014\mo8\dy30\hr15\min18}}"; 
	addString($infoTable);
}

/**
* -------------------WRITE THE SECTIONS NEEDED FOR THE RTF FORMAT -----------------------
*
*  This function needs to be at end because (like c language) it needs to refer to functions already specified earlier in file
* write the main part of the document by creating sub-parts and then adding together
* 
*/

function initRTFdoc ()
{
makeRTFstart();
makeStyleSheet();
makeListTable();
makeListOR();
makeInfoTable();
return;
}

/**
* ------------------------
* DEFINE paragraph control words needed to wrap around the style control words for each heading style in RTF format
* -----------------------
*
* These global variables will then be used to complete the sequence of RTF codewords in the addRTFpara function
*/

function SetRTFparas() //this used to be called setRTFparas()
{ 
global $T1,$Indent1,$B1,$Base1,$H1,$H2,$H3, $H4,$IndentStyle,$T1Style,$BaseStyle,$H1style, $H2style, $H3style, $H4style,$endPara;
$B1='{\b ';//note the space after the b codeword is essential
$Base1='{\pard\plain \ltrpar\s1'.$BaseStyle.' ';
$Indent1='{\pard\plain \ltrpar\s25'.$IndentStyle.' ';
$H1="{\pard\plain \ltrpar\s12".$H1style.' ';//we now have a string with the paragraph codes and H1 style codes, except the end para character
$H2="{\pard\plain \ltrpar\s11".$H2style." ";//the last space is needed to mark the start of non-control word text
$H3="{\pard\plain \ltrpar\s10".$H3style." ";//the last space is needed to mark the start of non-control word text
$H4="{\pard\plain \ltrpar\s9".$H4style." ";//the last space is needed to mark the start of non-control word text
$T1='{\trowd\ltrrow \trkeep\keepn\pard\plain \ts13'.$T1Style.' ';//Table row paragraph style with style information
$endPara=' \par}';
}
function applyRTFstyles() 
{ 
global $T1,$Indent1,$B1,$Base1,$H1,$H2,$H3, $H4,$IndentStyle,$T1Style,$BaseStyle,$H1style, $H2style, $H3style, $H4style,$endPara;
$B1='{\b ';//note the space after the b codeword is essential
$Base1='{\pard\plain \ltrpar\s1'.$BaseStyle.' ';
$Indent1='{\pard\plain \ltrpar\s25'.$IndentStyle.' ';
$H1="{\pard\plain \ltrpar\s12".$H1style.' ';//we now have a string with the paragraph codes and H1 style codes, except the end para character
$H2="{\pard\plain \ltrpar\s11".$H2style." ";//the last space is needed to mark the start of non-control word text
$H3="{\pard\plain \ltrpar\s10".$H3style." ";//the last space is needed to mark the start of non-control word text
$H4="{\pard\plain \ltrpar\s9".$H4style." ";//the last space is needed to mark the start of non-control word text
$T1='{\trowd\ltrrow \trkeep\keepn\pard\plain \ts13'.$T1Style.' ';//Table row paragraph style with style information
$endPara=' \par}';
}
/*
* Within $H1 is the $H1style.   Although we are notionally setting a style, we are also writing
* the defined style to the paragraph as if we were a Microsoft User "applying" the style at the outset.
* If this were not done, the style definition would simply show in the style inspector; the WYSIWYG formats would not reflect it.
*
* This is a function to add header pre-codes and paragraph end post-code for RTF; second argument is heading level  num
*/
function addRTFpara($InputMe,$num) 
{
	global $OutputMe, $H1,$H2,$H3,$H4,$endPara;
	if ($num==1)
	{$OutputMe=$H1.$InputMe.$endPara;
	}
	if ($num==2)
	{$OutputMe=$H2.$InputMe.$endPara;
	}
	if ($num==3)
	{$OutputMe=$H3.$InputMe.$endPara;
	}
	if ($num==4)
	{$OutputMe=$H4.$InputMe.$endPara;
	}
	addString($OutputMe);//add this string to the RTF file
	return;
}
function CodesParser (&$Parse)
{
global $Indent1,$B1,$H1,$H2,$H3,$H4,$endPara,$endBrace;
$Parse=str_replace("<TT>",$Indent1,$Parse);//This should replace the  <TT> placeholder with left bracket for RTF bolded word group.
$Parse=str_replace("</TT>",$endPara,$Parse);//This should replace the </TT> placeholder with RTF para formatting end para codeword]
$Parse=str_replace("<b>",$B1,$Parse);//This should replace the  <b> placeholder with left bracket for RTF bolded word group.
$Parse=str_replace("<h1>",$H1,$Parse);//This should replace the [[h1]] placeholder with RTF paraformatting for $H1]
$Parse=str_replace("<h2>",$H2,$Parse);//This should replace the [[h2]] placeholder with RTF paraformatting]
$Parse=str_replace("<h3>",$H3,$Parse);//This should replace the [[h3]] placeholder with RTF paraformatting]
$Parse=str_replace("<h4>",$H4,$Parse);//This should replace the [[h3]] placeholder with RTF paraformatting]
$Parse=str_replace("</b>",$endBrace,$Parse);//This should replace the  </b> placeholder with right curly bracket for RTF bolded word group.
$Parse=str_replace("</h>",$endPara,$Parse);//This should replace the </h> placeholder with RTF para formatting end para codeword]
//addString ($Parse);
return $Parse;
}
function docTitle()
{
	
}

/**
* This function will be used as a block of text for company signatures in an RTF document
*/

function CompanySign()
{
global $T1,$Base1,$SignTable,$endPara,$endBrace;
$SignTable='';
//the paragraph definition for table rows includes ts13 i.e. style 13 - so it needs to be in style sheet
//$tableRowDefs='\pard \trowd\ltrrow ';
//be careful with spacing in the cell definitions and cellx - leading spaces are important
$C1='(Signature of Director or Sole Director and Secretary or Authorised Representative)';
$C2=' ';
$C3='(Signature of Secretary or Other Director)';
$C7='(Name of Director or Sole Director and Secretary in full)';
$C9='(Name of Secretary or Other Director in full)';
//CDef1 has a doted border over the first cell
//CDef1B has a black line border over the first cell
/* column widths for Cambria 10 are clftsWidth3\clwWidth2714 but change Width to 3260 for Cambria 12 */
$CDef1='\clvertalt\clbrdrt\brdrdot\brdrw10 \clbrdrl\brdrtbl \clbrdrb\brdrtbl \clbrdrr\brdrtbl \cltxlrtb\clftsWidth3\clwWidth3260\clshdrawnil \cellx3565';
$CDef1B='\clvertalt\clbrdrt\brdrbl \clbrdrl\brdrtbl \clbrdrb\brdrtbl \clbrdrr\brdrtbl \cltxlrtb\clftsWidth3\clwWidth3260\clshdrawnil \cellx3565';
/* column widths for Cambria 10 are clftsWidth3\clwWidth345 but change Width to 425? for Cambria 12 */
$CDef2='\clvertalt\clbrdrt\brdtbl \clbrdrl\brdrtbl \clbrdrb\brdrtbl \clbrdrr\brdrtbl \cltxlrtb\clftsWidth3\clwWidth425\clshdrawnil \cellx3910';
/* column widths for Cambria 10 are clftsWidth3\clwWidth4101 but change Width to 3475 for Cambria 12 */
$CDef3='\clvertalt\clbrdrt\brdrdot\brdrw10 \clbrdrl\brdrtbl \clbrdrb\brdrtbl \clbrdrr\brdrtbl \cltxlrtb\clftsWidth3\clwWidth3475\clshdrawnil \cellx8011';
$CDef3B='\clvertalt\clbrdrt\brdrbl \clbrdrl\brdrtbl \clbrdrb\brdrtbl \clbrdrr\brdrtbl \cltxlrtb\clftsWidth3\clwWidth3475\clshdrawnil \cellx8011';
$CellDef1=$CDef1.$CDef2.$CDef3;
$CellDef2=$CDef1B.$CDef2.$CDef3B;
$tableRow1=$T1.$CellDef1.' \intbl '.$C1.' \cell \par \intbl '.$C2.' \cell \intbl '.$C3.' \cell \par \intbl \row }';  
//inserting \par inside cells may have the effect of increasing row height
$tableRow2=$T1.$CellDef2.'\intbl \cell \par \intbl \cell \par \intbl \cell \row}';  
$tableRow3=$T1.$CellDef1.'\intbl '.$C7.' \cell \par \intbl '.$C2.' \cell \intbl '.$C9.' \cell \par \intbl \row}\pard';  //important to end final row of table with a new para

//build table with each row of 3 cells
$SignTable=$SignTable.$tableRow1.$tableRow2.$tableRow3;
//now update the $RTFdata with Execution clause and sign table
addString($SignTable);
return;
}
/**
* This is a custom RTF paragraph for execution as an agreement
*/

function Execution()
{
	global $Base1,$Indent1;
	$Executed=$Indent1.'\page \par<b>EXECUTED AS AN AGREEMENT</b> \par}';
	$Executed=$Executed.$Indent1.'\par<b>Executed by PartyA</b> \par \par }';
	addString($Executed);
	addString(CompanySign());
	$Executed=$Indent1.'\par<b>Executed by PartyC</b> \keepn \par \keepn \par }';
	addString($Executed);
	addString(CompanySign());
}
function TextSwap(&$Parse,$Oldtext,$Newtext)
{
global $endPara,$endBrace;
$Parse=str_replace($Oldtext,$Newtext,$Parse);//This should replace the  partytext with new text
//addString ($Parse);
return $Parse;
}

/**
* Sequence of RTF code words for adding bold text to the argument for function
*/

function addBold($b)
{
	global $B1,$boldtext, $endBrace;
	$eb="}";
	$boldtext=$B1.$b.$endBrace;
	return $boldtext;
}	
/**
* ------------------------
* This function will write the endbrace to the string for the whole RTF file, then write the string to the disk file
* -----------------------
*
*/function writeRTFdoc ($YourData,$YourFilename)
{
global $stringData,$endBrace;
$stringData=$YourData.$endBrace;
$myFile = $YourFilename.'.rtf';
echo "<p> Writing file ".$myFile."</p>";

/**
* Open a file for writing.  Make sure permissions of folder are set correctly 
*/


$fh = fopen($myFile, 'w+') or die("can't open file"); //this w+ argument will re-write a file from scratch, or create one
//$stringData = $dateTime."\n";
fwrite($fh, $stringData);
//This will be the information read from the file
//$stringData = $lines;
//fwrite($fh, $stringData);
fclose($fh);
}
?>
