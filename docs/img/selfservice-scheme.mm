<map version="freeplane 1.7.0">
<!--To view this file, download free mind mapping software Freeplane from http://freeplane.sourceforge.net -->
<node TEXT="Selfservice&#xa;Extension" STYLE_REF="Fallunterscheidung" FOLDED="false" ID="ID_1996522231" CREATED="1697636951261" MODIFIED="1697637208792" STYLE="oval" VGAP_QUANTITY="33.74999899417165 pt">
<font SIZE="18"/>
<hook NAME="MapStyle">
    <properties edgeColorConfiguration="#808080ff,#ff0000ff,#0000ffff,#00ff00ff,#ff00ffff,#00ffffff,#7c0000ff,#00007cff,#007c00ff,#7c007cff,#007c7cff,#7c7c00ff" show_note_icons="true" fit_to_viewport="false"/>

<map_styles>
<stylenode LOCALIZED_TEXT="styles.root_node" STYLE="oval" UNIFORM_SHAPE="true" VGAP_QUANTITY="24.0 pt">
<font SIZE="24"/>
<stylenode LOCALIZED_TEXT="styles.predefined" POSITION="right" STYLE="bubble">
<stylenode LOCALIZED_TEXT="default" COLOR="#000000" STYLE="fork">
<font NAME="SansSerif" SIZE="10" BOLD="false" ITALIC="false"/>
</stylenode>
<stylenode LOCALIZED_TEXT="defaultstyle.details"/>
<stylenode LOCALIZED_TEXT="defaultstyle.attributes">
<font SIZE="9"/>
</stylenode>
<stylenode LOCALIZED_TEXT="defaultstyle.note" COLOR="#000000" BACKGROUND_COLOR="#ffffff" TEXT_ALIGN="LEFT"/>
<stylenode LOCALIZED_TEXT="defaultstyle.floating">
<edge STYLE="hide_edge"/>
<cloud COLOR="#f0f0f0" SHAPE="ROUND_RECT"/>
</stylenode>
</stylenode>
<stylenode LOCALIZED_TEXT="styles.user-defined" POSITION="right" STYLE="bubble">
<stylenode LOCALIZED_TEXT="styles.topic" COLOR="#18898b" STYLE="fork">
<font NAME="Liberation Sans" SIZE="10" BOLD="true"/>
</stylenode>
<stylenode LOCALIZED_TEXT="styles.subtopic" COLOR="#cc3300" STYLE="fork">
<font NAME="Liberation Sans" SIZE="10" BOLD="true"/>
</stylenode>
<stylenode LOCALIZED_TEXT="styles.subsubtopic" COLOR="#669900">
<font NAME="Liberation Sans" SIZE="10" BOLD="true"/>
</stylenode>
<stylenode LOCALIZED_TEXT="styles.important">
<icon BUILTIN="yes"/>
</stylenode>
</stylenode>
<stylenode LOCALIZED_TEXT="styles.AutomaticLayout" POSITION="right" STYLE="bubble">
<stylenode LOCALIZED_TEXT="AutomaticLayout.level.root" COLOR="#000000" STYLE="oval" SHAPE_HORIZONTAL_MARGIN="10.0 pt" SHAPE_VERTICAL_MARGIN="10.0 pt">
<font SIZE="18"/>
</stylenode>
<stylenode LOCALIZED_TEXT="AutomaticLayout.level,1" COLOR="#0033ff">
<font SIZE="16"/>
</stylenode>
<stylenode LOCALIZED_TEXT="AutomaticLayout.level,2" COLOR="#00b439">
<font SIZE="14"/>
</stylenode>
<stylenode LOCALIZED_TEXT="AutomaticLayout.level,3" COLOR="#990000">
<font SIZE="12"/>
</stylenode>
<stylenode LOCALIZED_TEXT="AutomaticLayout.level,4" COLOR="#111111">
<font SIZE="10"/>
</stylenode>
<stylenode LOCALIZED_TEXT="AutomaticLayout.level,5"/>
<stylenode LOCALIZED_TEXT="AutomaticLayout.level,6"/>
<stylenode LOCALIZED_TEXT="AutomaticLayout.level,7"/>
<stylenode LOCALIZED_TEXT="AutomaticLayout.level,8"/>
<stylenode LOCALIZED_TEXT="AutomaticLayout.level,9"/>
<stylenode LOCALIZED_TEXT="AutomaticLayout.level,10"/>
<stylenode LOCALIZED_TEXT="AutomaticLayout.level,11"/>
</stylenode>
</stylenode>
</map_styles>
</hook>
<node TEXT="Send E-Mail via selfservice" POSITION="right" ID="ID_1252093917" CREATED="1697636967835" MODIFIED="1697636999950">
<node LOCALIZED_STYLE_REF="defaultstyle.floating" ID="ID_564283080" CREATED="1692282076383" MODIFIED="1697637760443" BACKGROUND_COLOR="#99ccff" STYLE="bubble" HGAP_QUANTITY="39.4999992400408 pt" VSHIFT_QUANTITY="5.249999843537813 pt"><richcontent TYPE="NODE">

<html>
  <head>
    
  </head>
  <body>
    <p>
      <b>Webform (B) </b>
    </p>
    <p>
      
    </p>
    <p>
      Only asks for<br/>email address
    </p>
  </body>
</html>
</richcontent>
<node TEXT="Enter email&#xa;address" STYLE_REF="Automatischer Prozess" ID="ID_315789229" CREATED="1697636598516" MODIFIED="1697637776347" BACKGROUND_COLOR="#ffffff" STYLE="bubble">
<edge STYLE="bezier"/>
<node STYLE_REF="Automatischer Prozess" ID="ID_1976745577" CREATED="1692282611981" MODIFIED="1697637072552" VGAP_QUANTITY="21.749999351799506 pt" HGAP_QUANTITY="29.74999953061345 pt" VSHIFT_QUANTITY="0.0 pt" BACKGROUND_COLOR="#ffffcc" STYLE="bubble"><richcontent TYPE="NODE">

<html>
  <head>
    
  </head>
  <body>
    <p>
      <b>Formprocessor (D)</b>
    </p>
    <p>
      
    </p>
    <p>
      Action: Find contact<br/>related to email address
    </p>
  </body>
</html>
</richcontent>
<edge STYLE="bezier"/>
<node STYLE_REF="Fallunterscheidung" ID="ID_1592455216" CREATED="1692283159056" MODIFIED="1697636808506" BORDER_COLOR_LIKE_EDGE="false"><richcontent TYPE="NODE">

<html>
  <head>
    
  </head>
  <body>
    <p>
      <b>Unique</b>&#160;contact identified
    </p>
  </body>
</html>
</richcontent>
<edge STYLE="bezier" WIDTH="1"/>
<node TEXT="Send email containing&#xa;selfservice hash" STYLE_REF="Automatischer Prozess" ID="ID_715948293" CREATED="1697186211154" MODIFIED="1697637072555" BACKGROUND_COLOR="#ffffcc" STYLE="bubble"/>
</node>
<node STYLE_REF="Fallunterscheidung" ID="ID_1259444470" CREATED="1692283170985" MODIFIED="1697636808512" BORDER_COLOR_LIKE_EDGE="false"><richcontent TYPE="NODE">

<html>
  <head>
    
  </head>
  <body>
    <p>
      <b>Multiple</b>&#160;contacts identified
    </p>
  </body>
</html>
</richcontent>
<edge STYLE="bezier" WIDTH="1"/>
<node TEXT="Send email with&#xa;instructions what&#xa;to do in this case" STYLE_REF="Automatischer Prozess" ID="ID_831370225" CREATED="1697186211154" MODIFIED="1697637072559" BACKGROUND_COLOR="#ffffcc" STYLE="bubble"/>
</node>
<node STYLE_REF="Fallunterscheidung" ID="ID_1269290441" CREATED="1692283128624" MODIFIED="1697636808513" BORDER_COLOR_LIKE_EDGE="false"><richcontent TYPE="NODE">

<html>
  <head>
    
  </head>
  <body>
    <p>
      <b>No</b>&#160;contacts identified
    </p>
  </body>
</html>
</richcontent>
<edge STYLE="bezier" WIDTH="1"/>
<node TEXT="Send email with link&#xa;to registration form" STYLE_REF="Automatischer Prozess" ID="ID_1997428757" CREATED="1697186211154" MODIFIED="1697637072564" BACKGROUND_COLOR="#ffffcc" STYLE="bubble"/>
</node>
</node>
</node>
</node>
</node>
<node TEXT="Open Webform with&#xa;prefilled data" POSITION="right" ID="ID_1400766296" CREATED="1697636981696" MODIFIED="1697637032330">
<node TEXT="Open link containing&#xa;the selfservice hash" LOCALIZED_STYLE_REF="defaultstyle.floating" ID="ID_1631013222" CREATED="1692282076383" MODIFIED="1697637032325" BACKGROUND_COLOR="#ffffff" STYLE="bubble" HGAP_QUANTITY="72.4999982565642 pt" VSHIFT_QUANTITY="-27.749999172985582 pt">
<node STYLE_REF="Automatischer Prozess" ID="ID_1159334986" CREATED="1692285880006" MODIFIED="1697702447478" BACKGROUND_COLOR="#ffffcc" STYLE="bubble"><richcontent TYPE="NODE">

<html>
  <head>
    
  </head>
  <body>
    <p>
      <b>Formprocessor (C) </b>
    </p>
    <p>
      <br/>
      
    </p>
    <p>
      Retrieval auf defaults via<br/>identification of contact<br/>belonging to the hash
    </p>
  </body>
</html>

</richcontent>
<edge STYLE="bezier" WIDTH="3"/>
<node STYLE_REF="Manuelle Interaktion" ID="ID_1132511795" CREATED="1692283159056" MODIFIED="1697637072570" BACKGROUND_COLOR="#99ccff" STYLE="bubble"><richcontent TYPE="NODE">

<html>
  <head>
    
  </head>
  <body>
    <p>
      <b>Webform (A)</b><br/>&#160;
    </p>
    <p>
      Form with prefilled data.
    </p>
    <p>
      Fields can be readable,<br/>writable, required.<br/>
    </p>
  </body>
</html>
</richcontent>
<edge STYLE="bezier" WIDTH="thin"/>
<node TEXT="Change and&#xa;submit data" STYLE_REF="Automatischer Prozess" ID="ID_750059441" CREATED="1697636598516" MODIFIED="1697637097745" BACKGROUND_COLOR="#ffffff" STYLE="bubble">
<node STYLE_REF="Automatischer Prozess" ID="ID_414873415" CREATED="1697636613381" MODIFIED="1697637619052" BACKGROUND_COLOR="#ffffcc" STYLE="bubble"><richcontent TYPE="NODE">

<html>
  <head>
    
  </head>
  <body>
    <p>
      <b>Formprocessor (C)</b>
    </p>
    <p>
      
    </p>
    <p>
      Actions how to proceed with the data.
    </p>
    <p>
      For example overwrite all fields with given data.<br/>
    </p>
  </body>
</html>
</richcontent>
</node>
</node>
</node>
</node>
</node>
</node>
</node>
</map>
