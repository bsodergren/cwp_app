; Script generated by the Inno Setup Script Wizard.
; SEE THE DOCUMENTATION FOR DETAILS ON CREATING INNO SETUP SCRIPT FILES!

[Setup]
; NOTE: The value of AppId uniquely identifies this application. Do not use the same AppId value in installers for other applications.
; (To generate a new GUID, click Tools | Generate GUID inside the IDE.)
AppId={{AECEFE49-62E6-4B55-87F4-8186EAC62508}
AppName=Media Creator
AppVersion=2.4
AppPublisher=Bjorn Sodergren
AppPublisherURL=https://www.sodergren.us
AppSupportURL=https://www.sodergren.us
AppUpdatesURL=https://www.sodergren.us

DefaultDirName={autopf}\MediaCreator
DefaultGroupName=MediaCreator
; Remove the following line to run in administrative install mode (install for all users.)
PrivilegesRequired=lowest
PrivilegesRequiredOverridesAllowed=dialog
OutputDir=D:\development\Installers

OutputBaseFilename=mediacreatorsetup
Compression=lzma
SolidCompression=yes
WizardStyle=modern

[Languages]
Name: "english"; MessagesFile: "compiler:Default.isl"

[Tasks]
Name: "desktopicon"; Description: "{cm:CreateDesktopIcon}"; GroupDescription: "{cm:AdditionalIcons}"; Flags: unchecked

[Dirs]
Name: "{app}\public";Permissions: users-full
Name: "{app}\public\src";Permissions: users-full
[Files]

Source: "D:\development\cwp_app\*"; DestDir: "{app}"; Excludes: "*.log,.git*,.idea*,.vscode,settings.json"; Flags: ignoreversion ;Permissions: users-full
Source: "D:\development\cwp_app\settings.install"; DestName: "settings.json"; DestDir: "{app}";Permissions: users-full

Source: "D:\development\cwp_app\php\*"; DestDir: "{app}\php"; Excludes: ""; Flags: ignoreversion recursesubdirs createallsubdirs;Permissions: users-full
Source: "D:\development\cwp_app\bin\*"; DestDir: "{app}\bin"; Excludes: ""; Flags: ignoreversion recursesubdirs createallsubdirs ;Permissions: users-full
Source: "D:\development\cwp_app\locales\*"; DestDir: "{app}\locales"; Excludes: ""; Flags: ignoreversion recursesubdirs createallsubdirs ;Permissions: users-full
;Source: "D:\development\cwp_app\scripts\*"; DestDir: "{app}\scripts"; Excludes: ""; Flags: ignoreversion recursesubdirs createallsubdirs

Source: "D:\development\cwp_app\public\*"; DestDir: "{app}\public"; Excludes: "*.log,.git*,.idea*,.vscode,test_navlinks.*,config.ini"; Flags: ignoreversion ;Permissions: users-full
Source: "D:\development\cwp_app\public\config.install"; DestName: "config.ini"; DestDir: "{app}\public";Permissions: users-full
Source: "D:\development\cwp_app\public\src\*"; DestDir: "{app}\public\src"; Excludes: ""; Flags: ignoreversion    recursesubdirs createallsubdirs;Permissions: users-full

Source: "D:\development\cwp_app\public\www\*"; DestDir: "{app}\public\www"; Excludes: ""; Flags: ignoreversion   ;Permissions: users-full
Source: "D:\development\cwp_app\public\www\assets\*"; DestDir: "{app}\public\www\assets"; Excludes: ""; Flags: ignoreversion recursesubdirs createallsubdirs  ;Permissions: users-full
Source: "D:\development\cwp_app\public\www\settings\*"; DestDir: "{app}\public\www\settings"; Excludes: ""; Flags: ignoreversion recursesubdirs createallsubdirs   ;Permissions: users-full
Source: "D:\development\cwp_app\public\www\updater\*"; DestDir: "{app}\public\www\updater"; Excludes: ""; Flags: ignoreversion recursesubdirs createallsubdirs    ;Permissions: users-full

[Icons]
Name: "{group}\Media Creator"; Filename: "{app}\MediaCreator.exe"
Name: "{autodesktop}\Media Creator"; Filename: "{app}\MediaCreator.exe"; Tasks: desktopicon

[Run]
Filename: "{app}\MediaCreator.exe"; Description: "{cm:LaunchProgram,Media Creator}"; Flags: nowait postinstall skipifsilent