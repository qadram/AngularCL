{ *******************************************************************

  Embarcadero Technologies 2012

  developer: Pablo Mateos

  *******************************************************************}

unit uHTML5PropertyEditors;

interface

uses
  Windows, Classes, vcl.Dialogs, vcl.Controls,
  vcl.Forms, vcl.Graphics, SysUtils,
  vcl.ComCtrls, vcl.valedit, vcl.grids, vcl.FileCtrl, AnsiStrings,
  uArrayEditor, IDEInterface, uNativePropertyEditors, uSelectFileDialog,
  StdCtrls, ExtCtrls, ExtDlgs, uCss3PropertyEditor;

type
  THTML5CustomMediaPropertyEditor = class( TArrayPropertyEditor )
  private
    latestkeyname: string;
  public
    procedure beforeShowEditor( dialog: TForm ); override;
    procedure vePropertiesSelectCell( Sender: TObject; ACol, ARow: Integer; var CanSelect: Boolean );
    procedure vePropertiesEditButtonClick( Sender: TObject );
    procedure vePropertiesGetPickList( Sender: TObject; const KeyName: string; Values: TStrings );
    procedure GetNewItemCaption( Sender: TObject; var itemcaption: string );
  end;

  TCSS3PropertyEditor = class( TD4PHPPropertyEditor )
  public
    function getStyle: TD4PHPPropertyEditorStyles; override;
    function Execute( value: string; out newvalue: string ): Boolean; override;
    procedure beforeShowEditor( dialog: TForm ); virtual; abstract;
  end;

  TCSS3BorderRadiusPropertyEditor = class( TCSS3PropertyEditor )
  public
    procedure beforeShowEditor( dialog: TForm ); override;
  end;

  TCSS3GradientPropertyEditor = class( TCSS3PropertyEditor )
  public
    procedure beforeShowEditor( dialog: TForm ); override;
  end;

  TCSS3TransformPropertyEditor = class( TCSS3PropertyEditor )
  public
    procedure beforeShowEditor( dialog: TForm ); override;
  end;

  TCSS3TextShadowPropertyEditor = class( TCSS3PropertyEditor )
  public
    procedure beforeShowEditor( dialog: TForm ); override;
  end;

  TCSS3BoxShadowPropertyEditor = class( TCSS3PropertyEditor )
  public
    procedure beforeShowEditor( dialog: TForm ); override;
  end;

  TCSS3AnimationsPropertyEditor = class( TArrayPropertyEditor )
    // TCSS3AnimationsPropertyEditor = class( TD4PHPPropertyEditor )
  private
    latestkeyname: string;
  public
    function Execute( value: string; out newvalue: string ): Boolean; override;
    procedure beforeShowEditor( dialog: TForm ); override;
    procedure vePropertiesSelectCell( Sender: TObject; ACol, ARow: Integer; var CanSelect: Boolean );
    procedure vePropertiesEditButtonClick( Sender: TObject );
    procedure vePropertiesGetPickList( Sender: TObject; const KeyName: string; Values: TStrings );
    procedure GetNewItemCaption( Sender: TObject; var itemcaption: string );
  end;

implementation

uses
  uCss3BorderRadius, uCss3Gradient, uCss3BoxShadow, uCss3Transform, uCss3TextShadow, uAnimationsPropertyEditor,
  uWebColorPropertyEditor;


// THTML5MediaPropertyEditor
procedure THTML5CustomMediaPropertyEditor.beforeShowEditor( dialog: TForm );
begin
  inherited;
  latestkeyname := '';
  with dialog as TArrayEditorDlg do
  begin
    OnGetNewItemCaption := GetNewItemCaption;
    caption := 'Media';
    btnNewSubItem.Visible := false;
    btnLoad.Visible := false;
    captionproperty := 'Caption';
    btnDelete.Top := btnNewSubItem.Top;
    with defaultproperties do
    begin
      add( 'Caption=' );
      add( 'Source=' );
      add( 'Mediatype=' );
      add( 'Codecs=' );
    end;
    veProperties.FixedCols := 1;
    veProperties.OnEditButtonClick := vePropertiesEditButtonClick;
    veProperties.OnGetPickList := vePropertiesGetPickList;
    veProperties.OnSelectCell := vePropertiesSelectCell;
  end;
end;

procedure THTML5CustomMediaPropertyEditor.GetNewItemCaption( Sender: TObject;
  var itemcaption: string );
begin
  itemcaption := 'Media' + inttostr( ( Sender as TArrayEditorDlg ).tvItems.Items.Count + 1 );
end;

procedure THTML5CustomMediaPropertyEditor.vePropertiesSelectCell(
  Sender: TObject; ACol, ARow: Integer; var CanSelect: Boolean );
begin
  latestkeyname := ( Sender as TValueListEditor ).Cells[0, ARow];
end;

procedure THTML5CustomMediaPropertyEditor.vePropertiesEditButtonClick( Sender: TObject );
var
  value, newvalue, sf: string;
begin
  if ( latestkeyname = 'Source' ) then
  begin
    sf := scriptFileName;
    value := ( Sender as TValueListEditor ).Values[latestkeyname];
    with TFileNamePropertyEditor.Create( application ) do
    begin
      try
        Filter := 'All Files (*.*)|*.*|Video files(*.ogg; *.ogv; *.mp4; *.webm; *.avi; *.wmv; *.mov; *.mpeg)|*.ogg; *.ogv; *.mp4; *.webm; *.avi; *.wmv; *.mov; *.mpeg|' +
          'Audio files(*.mp3; *.wav; *.ogg; *.aiff)|*.mp3; *.wav; *.ogg; *.aiff';
        FileName := sf;
        if Execute( value, newvalue ) then
          ( Sender as TValueListEditor ).Values[latestkeyname] := newvalue;
      finally
        Free;
      end;
    end;
  end;
end;

procedure THTML5CustomMediaPropertyEditor.vePropertiesGetPickList(
  Sender: TObject; const KeyName: string; Values: TStrings );
begin
  if ( KeyName = 'Source' ) then
  begin
    ( Sender as TValueListEditor ).ItemProps[KeyName].EditStyle := esEllipsis;
  end;

  if ( KeyName = 'Mediatype' ) then
  begin
    with Values do
    begin
      add( 'mtVideoOgg' );
      add( 'mtVideoOgv' );
      add( 'mtVideoMp4' );
      add( 'mtVideoWebm' );
      add( 'mtAudioOgg' );
      add( 'mtAudioMpeg' );
      add( 'mtAudioWav' );
    end;
  end;
end;


{ TCSS3PropertyEditor }

function TCSS3PropertyEditor.Execute( value: string;
  out newvalue: string ): Boolean;
var
  subproperties: TStringList;
  dialog: TfrmCss3PropertyEditor;
begin
  subproperties := TStringList.Create;
  try
    subproperties.Text := value;
    dialog := TfrmCss3PropertyEditor.Create( application );
    dialog.HTMLCode := GetCurrentComponentHTML;
    dialog.CSSID := GetCurrentComponentCSSID;
    dialog.Properties := subproperties;
    dialog.ModulePath := ExtractFilePath( GetCurrentModuleFilename );
    with dialog do
    begin
      try
        beforeShowEditor( dialog );
        if ( showmodal = mrOK ) then
        begin
          newvalue := subproperties.Text;
          result := true;
        end;
      finally
        Free;
      end;
    end;

  finally
    subproperties.Free;
  end;
end;

function TCSS3PropertyEditor.getStyle: TD4PHPPropertyEditorStyles;
begin
  result := [];
end;

{ TCSS3BorderRadiusPropertyEditor }

procedure TCSS3BorderRadiusPropertyEditor.beforeShowEditor( dialog: TForm );
begin
  ( dialog as TfrmCss3PropertyEditor ).AssignFrameEffect( TframeCss3BorderRadius );
end;

{ TCSS3GradientPropertyEditor }

procedure TCSS3GradientPropertyEditor.beforeShowEditor( dialog: TForm );
begin
  ( dialog as TfrmCss3PropertyEditor ).AssignFrameEffect( TframeCss3Gradient );
end;

{ TCSS3TransformPropertyEditor }

procedure TCSS3TransformPropertyEditor.beforeShowEditor( dialog: TForm );
begin
  ( dialog as TfrmCss3PropertyEditor ).AssignFrameEffect( TframeCss3Transform );
end;

{ TCSS3TextShadowPropertyEditor }

procedure TCSS3TextShadowPropertyEditor.beforeShowEditor( dialog: TForm );
begin
  ( dialog as TfrmCss3PropertyEditor ).AssignFrameEffect( TframeCss3TextShadow );
end;

{ TCSS3BoxShadowPropertyEditor }

procedure TCSS3BoxShadowPropertyEditor.beforeShowEditor( dialog: TForm );
begin
  ( dialog as TfrmCss3PropertyEditor ).AssignFrameEffect( TframeCss3BoxShadow );
end;

{ TCSS3AnimationsPropertyEditor }

function TCSS3AnimationsPropertyEditor.Execute( value: string;
  out newvalue: string ): Boolean;
begin
  DialogClass := TfrmAnimationsPropertyEditor;
  result := inherited;
end;

procedure TCSS3AnimationsPropertyEditor.beforeShowEditor( dialog: TForm );
begin
  inherited;
  latestkeyname := '';
  with dialog as TfrmAnimationsPropertyEditor do
  begin
//    HTMLCode := GetCurrentComponentHTML;
//    CSSID := GetCurrentComponentCSSID;
    OnGetNewItemCaption := GetNewItemCaption;
    Css3Preview.ModulePath := ExtractFilePath( GetCurrentModuleFilename );
    caption := 'CSS3 Animation';
    btnNewSubItem.Visible := false;
    btnLoad.Visible := false;
    captionproperty := 'Caption';
    btnDelete.Top := btnNewSubItem.Top;
    with defaultproperties do
    begin
      add( 'Caption=' );
      add( 'Animation Duration=1.5s' );
      add( 'Iteration Count=1' );
      add( 'Animation Timing=ease' );
      add( 'Fill Mode=none' );
      add( 'Start Rotate=0deg' );
      add( 'End Rotate=360deg' );
      add( 'Start Scale=1' );
      add( 'End Scale=1' );
      add( 'Start Skew=0deg' );
      add( 'End Skew=0deg' );
      add( 'Start Translate=0px' );
      add( 'End Translate=0px' );
      add( 'Start Color=' );
      add( 'End Color=' );
    end;
    veProperties.FixedCols := 1;
    veProperties.OnEditButtonClick := vePropertiesEditButtonClick;
    veProperties.OnGetPickList := vePropertiesGetPickList;
    veProperties.OnSelectCell := vePropertiesSelectCell;
  end;
end;

procedure TCSS3AnimationsPropertyEditor.GetNewItemCaption( Sender: TObject;
  var itemcaption: string );
begin
  itemcaption := 'Animation' + inttostr( ( Sender as TArrayEditorDlg ).tvItems.Items.Count + 1 );
end;

procedure TCSS3AnimationsPropertyEditor.vePropertiesSelectCell(
  Sender: TObject; ACol, ARow: Integer; var CanSelect: Boolean );
begin
  latestkeyname := ( Sender as TValueListEditor ).Cells[0, ARow];
end;

procedure TCSS3AnimationsPropertyEditor.vePropertiesEditButtonClick( Sender: TObject );
var
  value: string;
begin
  if ( latestkeyname = 'Start Color' ) or ( latestkeyname = 'End Color' ) then
  begin
    value := ( Sender as TValueListEditor ).Values[latestkeyname];
    with TWebColorPropertyEditor.Create( nil ) do
    begin
      try
        WebColor := value;
        if Edit then
          ( Sender as TValueListEditor ).Values[latestkeyname] := WebColor;
      finally
        Free;
      end;
    end;
  end;
end;

procedure TCSS3AnimationsPropertyEditor.vePropertiesGetPickList(
  Sender: TObject; const KeyName: string; Values: TStrings );
begin
  if ( KeyName = 'Animation Timing' ) then
  begin
    with Values do
    begin
      add( 'ease' );
      add( 'linear' );
      add( 'ease-in' );
      add( 'ease-out' );
      add( 'ease-in-out' );
    end;
  end;

  if ( KeyName = 'Fill Mode' ) then
  begin
    with Values do
    begin
      add( 'none' );
      add( 'forwards' );
      add( 'backwards' );
      add( 'both' );
    end;
  end;

  if ( KeyName = 'Start Color' ) or ( KeyName = 'End Color' ) then
  begin
    ( Sender as TValueListEditor ).ItemProps[KeyName].EditStyle := esEllipsis;
  end;
end;

initialization

registerPropertyEditor( 'CustomMedia', 'Sources', THTML5CustomMediaPropertyEditor );
registerPropertyEditor( 'Control', 'BorderRadius', TCSS3BorderRadiusPropertyEditor );
registerPropertyEditor( 'Control', 'Gradient', TCSS3GradientPropertyEditor );
registerPropertyEditor( 'Control', 'Transform', TCSS3TransformPropertyEditor );
registerPropertyEditor( 'Control', 'TextShadow', TCSS3TextShadowPropertyEditor );
registerPropertyEditor( 'Control', 'BoxShadow', TCSS3BoxShadowPropertyEditor );
registerPropertyEditor( 'Animation', 'Items', TCSS3AnimationsPropertyEditor );


end.
