<html>
<head>

  <title>CS Fair</title>
  <style>
    html, body {text-align: center;}
  </style>
</head>

<?php
$h = 150;
$w = 238;
$x = 64; //x
$x1 = 64; //x
$y1 = -140;// y
$y2 = -164;//y2
$yy1 = -140;//y
$yy2 = -164;//y2
$row = 0;
$cnt = 1;
$surname = '';
$name = '';
$dir = __DIR__;
$ups = $dir . '/files';

echo '<a href="template.ait" download> Download the Template</a><br><br>';
$input = 'var myFile = File.openDialog("Select the Template");
      if(myFile != null)
      var docRef= app.open(myFile);';

if (($handle = fopen($ups . "/file.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
				$name = $data[0];
				$surname = $data[1];

				if($row==0){

				}
        else{
					if($row%3 == 0 ){
						$y1 -= $h;
						$y2 -= $h;
						$x1 = $x;
					}
          else{
						$x1 += $w;
					}

				}

        //name part
				$input = $input .  'var pathRef = docRef.pathItems.rectangle(' . $y1 . ' ,' . $x1 . ', 238, 25 );
      				var textRef = docRef.textFrames.areaText (pathRef);
      				textRef.textRange.characterAttributes.size=22;
      				textRef.paragraphs.add("' . $name . '");
      				var paraAttr_0 = textRef.paragraphs[0].paragraphAttributes;
      				paraAttr_0.justification = Justification.CENTER;';
        //surname part
				$input = $input . 'var pathRef2 = docRef.pathItems.rectangle( ' . $y2 . ' ,' . $x1 . ', 238, 25 );
      				var textRef2 = docRef.textFrames.areaText (pathRef2);
      				textRef2.textRange.characterAttributes.size=22;
      				textRef2.paragraphs.add("' . $surname . '");
      				var paraAttr_1 = textRef2.paragraphs[0].paragraphAttributes;
      				paraAttr_1.justification = Justification.CENTER;' . "<br />\n";

        $cnt++;

        if($cnt == 22){  //for new template
          $cnt = 1;
          $y1 = $yy1+$h;
          $y2 = $yy2+$h;
          $x1 = $x;
          $input = $input . 'var docRef= app.open(myFile);';
          }

          $row++;
    }//while
    fclose($handle);


}

$input = $input . "
try {

  if (app.documents.length > 0 ) {

    var destFolder = null;
    destFolder = Folder.selectDialog( 'Select folder for PDF files.', '~' );
    if (destFolder != null) {
      var options, i, sourceDoc, targetFile;
      options = this.getOptions();
      for ( i = 0; i < app.documents.length; i++ ) {
        sourceDoc = app.documents[i];
        targetFile = this.getTargetFile(sourceDoc.name, '.pdf', destFolder);
        sourceDoc.saveAs( targetFile, options );
      }
      alert( 'Documents saved as PDF' );
    }
  }
  else{
    throw new Error('There are no document open!');
  }
}
catch(e) {
  alert( e.message, 'Script Alert', true);
}
function getOptions()
{
  var options = new PDFSaveOptions();

  return options;
}
function getTargetFile(docName, ext, destFolder) {
  var newName = '';
  if (docName.indexOf('.') < 0) {
    newName = docName + ext;
  } else {
    var dot = docName.lastIndexOf('.');
    newName += docName.substring(0, dot);
    newName += ext;
  };

  var myFile = new File( destFolder + '/' + newName );

  if (myFile.open('w')) {
    myFile.close();
  }
  else {
    throw new Error('Access is denied');
  }
  return myFile;
}
";


$fp = fopen("files/script.jsx", "w");
$fwrite = fwrite($fp, $input);
echo '<a href="files/script.jsx" download>Download the Script</a><br><br>';

?>
