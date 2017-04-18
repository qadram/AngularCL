unit uZendPropertyEditors;

interface

uses
  Windows, Classes, Dialogs, Controls,
  Forms, Graphics, SysUtils, IDEInterface,
  ComCtrls, valedit, grids, FileCtrl, AnsiStrings, uArrayEditor,
  uNativePropertyEditors, uvaluelistedit;


type
  TZMailPropertyEditor = class( TValueListPropertyEditor )
  public
    function Execute( value: string; out newvalue: string ): boolean; override;
  end;

  TZFeedAuthorsPropertyEditor = class( TArrayPropertyEditor )
  private
    latestkeyname: string;
  public
    procedure beforeShowEditor( dialog: TForm ); override;
    procedure vePropertiesSelectCell( Sender: TObject; ACol, ARow: Integer; var CanSelect: boolean );
    procedure GetNewItemCaption( Sender: TObject; var itemcaption: string );
  end;

  TZFeedLinksPropertyEditor = class( TArrayPropertyEditor )
  private
    latestkeyname: string;
  public
    procedure beforeShowEditor( dialog: TForm ); override;
    procedure vePropertiesSelectCell( Sender: TObject; ACol, ARow: Integer; var CanSelect: boolean );
    procedure GetNewItemCaption( Sender: TObject; var itemcaption: string );
    procedure vePropertiesGetPickList( Sender: TObject; const KeyName: string; Values: TStrings );
  end;

  TZFeedImagePropertyEditor = class( TArrayPropertyEditor )
  private
    latestkeyname: string;
  public
    procedure beforeShowEditor( dialog: TForm ); override;
    procedure vePropertiesSelectCell( Sender: TObject; ACol, ARow: Integer; var CanSelect: boolean );
    procedure GetNewItemCaption( Sender: TObject; var itemcaption: string );
  end;

  TZFeedCategoriesPropertyEditor = class( TArrayPropertyEditor )
  private
    latestkeyname: string;
  public
    procedure beforeShowEditor( dialog: TForm ); override;
    procedure vePropertiesSelectCell( Sender: TObject; ACol, ARow: Integer; var CanSelect: boolean );
    procedure GetNewItemCaption( Sender: TObject; var itemcaption: string );
  end;

implementation


// TZFeedAuthorsPropertyEditor
procedure TZFeedAuthorsPropertyEditor.beforeShowEditor( dialog: TForm );
begin
  inherited;
  latestkeyname := '';
  with dialog as TArrayEditorDlg do
  begin
    OnGetNewItemCaption := GetNewItemCaption;
    caption := 'Authors info';
    btnNewSubItem.Visible := false;
    btnLoad.Visible := false;
    captionproperty := 'Name';
    btnDelete.Top := btnNewSubItem.Top;
    with defaultproperties do
    begin
      add( 'Email=' );
      add( 'Uri=' );
      add( 'Name=' );
    end;
    veProperties.FixedCols := 1;
    veProperties.OnSelectCell := vePropertiesSelectCell;
  end;
end;

procedure TZFeedAuthorsPropertyEditor.GetNewItemCaption( Sender: TObject;
  var itemcaption: string );
begin
  itemcaption := 'Author' + inttostr( ( Sender as TArrayEditorDlg ).tvItems.Items.Count + 1 );
end;

procedure TZFeedAuthorsPropertyEditor.vePropertiesSelectCell(
  Sender: TObject; ACol, ARow: Integer; var CanSelect: boolean );
begin
  latestkeyname := ( Sender as TValueListEditor ).Cells[0, ARow];
end;

// TZFeedLinksPropertyEditor
procedure TZFeedLinksPropertyEditor.beforeShowEditor( dialog: TForm );
begin
  inherited;
  latestkeyname := '';
  with dialog as TArrayEditorDlg do
  begin
    OnGetNewItemCaption := GetNewItemCaption;
    caption := 'Feedlinks info';
    btnNewSubItem.Visible := false;
    btnLoad.Visible := false;
    captionproperty := 'Value';
    btnDelete.Top := btnNewSubItem.Top;
    with defaultproperties do
    begin
      add( 'Uri=' );
      add( 'Type=' );
      add( 'Value=' );
    end;
    veProperties.FixedCols := 1;
    veProperties.OnGetPickList := vePropertiesGetPickList;
    veProperties.OnSelectCell := vePropertiesSelectCell;
  end;
end;

procedure TZFeedLinksPropertyEditor.GetNewItemCaption( Sender: TObject;
  var itemcaption: string );
begin
  itemcaption := 'Feedlinks' + inttostr( ( Sender as TArrayEditorDlg ).tvItems.Items.Count + 1 );
end;

procedure TZFeedLinksPropertyEditor.vePropertiesSelectCell(
  Sender: TObject; ACol, ARow: Integer; var CanSelect: boolean );
begin
  latestkeyname := ( Sender as TValueListEditor ).Cells[0, ARow];
end;

procedure TZFeedLinksPropertyEditor.vePropertiesGetPickList(
  Sender: TObject; const KeyName: string; Values: TStrings );
begin
  if ( KeyName = 'Type' ) then
  begin
    with Values do
    begin
      add( 'atom' );
      add( 'rss' );
      add( 'rdf' );
    end;
  end;
end;

// TZFeedImagePropertyEditor
procedure TZFeedImagePropertyEditor.beforeShowEditor( dialog: TForm );
begin
  inherited;
  latestkeyname := '';
  with dialog as TArrayEditorDlg do
  begin
    OnGetNewItemCaption := GetNewItemCaption;
    caption := 'Image info';
    btnNewSubItem.Visible := false;
    btnLoad.Visible := false;
    captionproperty := 'Value';
    btnDelete.Top := btnNewSubItem.Top;
    with defaultproperties do
    begin
      add( 'Uri=' );
      add( 'Link=' );
      add( 'Title=' );
      add( 'Description=' );
      add( 'Height=' );
      add( 'Width=' );
      add( 'Value=' );
    end;
    veProperties.FixedCols := 1;
    veProperties.OnSelectCell := vePropertiesSelectCell;
  end;
end;

procedure TZFeedImagePropertyEditor.GetNewItemCaption( Sender: TObject;
  var itemcaption: string );
begin
  itemcaption := 'Image' + inttostr( ( Sender as TArrayEditorDlg ).tvItems.Items.Count + 1 );
end;

procedure TZFeedImagePropertyEditor.vePropertiesSelectCell(
  Sender: TObject; ACol, ARow: Integer; var CanSelect: boolean );
begin
  latestkeyname := ( Sender as TValueListEditor ).Cells[0, ARow];
end;

// TZFeedCategoriesPropertyEditor
procedure TZFeedCategoriesPropertyEditor.beforeShowEditor( dialog: TForm );
begin
  inherited;
  latestkeyname := '';
  with dialog as TArrayEditorDlg do
  begin
    OnGetNewItemCaption := GetNewItemCaption;
    caption := 'Categories info';
    btnNewSubItem.Visible := false;
    btnLoad.Visible := false;
    captionproperty := 'Value';
    btnDelete.Top := btnNewSubItem.Top;
    with defaultproperties do
    begin
      add( 'Term=' );
      add( 'Label=' );
      add( 'Scheme=' );
      add( 'Value=' );
    end;
    veProperties.FixedCols := 1;
    veProperties.OnSelectCell := vePropertiesSelectCell;
  end;
end;

procedure TZFeedCategoriesPropertyEditor.GetNewItemCaption( Sender: TObject;
  var itemcaption: string );
begin
  itemcaption := 'Category' + inttostr( ( Sender as TArrayEditorDlg ).tvItems.Items.Count + 1 );
end;

procedure TZFeedCategoriesPropertyEditor.vePropertiesSelectCell(
  Sender: TObject; ACol, ARow: Integer; var CanSelect: boolean );
begin
  latestkeyname := ( Sender as TValueListEditor ).Cells[0, ARow];
end;


{ TZMailPropertyEditor }

function TZMailPropertyEditor.Execute( value: string;
  out newvalue: string ): boolean;
var
  strings, Titles: TStringList;

begin
  newvalue := value;
  strings := TStringList.create;
  Titles := TStringList.create;
  try
    ArrayToStringList( value, strings );
    with TValueListEditDlg.create( application ) do
    begin
      try
        Titles.add( 'Email' );
        Titles.add( 'Name' );
        ColumnsCaptions := Titles;
        veEditor.strings.Assign( strings );
        result := ( showmodal = mrOK );
        if ( result ) then
        begin
          newvalue := StringListToArray( veEditor.strings );
        end;
      finally
        free;
      end;
    end;
  finally
    strings.free;
    Titles.free;
  end;
end;

initialization

registerPropertyEditor( 'ZFeedWriter', 'AuthorsFeed', TZFeedAuthorsPropertyEditor );
registerPropertyEditor( 'ZFeedWriter', 'FeedLinksFeed', TZFeedLinksPropertyEditor );
registerPropertyEditor( 'ZFeedWriter', 'ImagesFeed', TZFeedImagePropertyEditor );
registerPropertyEditor( 'ZFeedWriter', 'CategoriesFeed', TZFeedCategoriesPropertyEditor );
registerPropertyEditor( 'ZBarcode', 'FontPath', TDirectoryPropertyEditor );
registerPropertyEditor( 'ZMail', 'To', TZMailPropertyEditor );
registerPropertyEditor( 'ZMail', 'Cc', TZMailPropertyEditor );
registerPropertyEditor( 'ZMail', 'Bcc', TZMailPropertyEditor );

end.
