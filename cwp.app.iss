; Script generated by the Inno Setup Script Wizard.
; SEE THE DOCUMENTATION FOR DETAILS ON CREATING INNO SETUP SCRIPT FILES!

[Setup]
; NOTE: The value of AppId uniquely identifies this application. Do not use the same AppId value in installers for other applications.
; (To generate a new GUID, click Tools | Generate GUID inside the IDE.)
AppId={{C5A769F9-275A-4774-8ACC-2C3FA0775B1C}


AppName=Media Creator
AppVersion=1.5
AppPublisher=Bjorn Sodergren
AppPublisherURL=https://www.sodergren.us
AppSupportURL=https://www.sodergren.us
AppUpdatesURL=https://www.sodergren.us
DefaultDirName={autopf}\MediaCreator
DisableProgramGroupPage=yes
; Remove the following line to run in administrative install mode (install for all users.)
PrivilegesRequired=lowest
PrivilegesRequiredOverridesAllowed=dialog
OutputDir=D:\development\Installers
OutputBaseFilename=mediacreator_installer
Compression=lzma
SolidCompression=yes
WizardStyle=modern

[Languages]
Name: "english"; MessagesFile: "compiler:Default.isl"

[Tasks]
Name: "desktopicon"; Description: "{cm:CreateDesktopIcon}"; GroupDescription: "{cm:AdditionalIcons}"; Flags: unchecked

[Files]
Source: "D:\development\cwp_app\*"; DestDir: "{app}"; Excludes: "cwp_app.iss,*.log,.git*,.idea*,.vscode,*\logs\*,*Media Load Flags\*,old_ext\*,cwp_sqlite.*,.webcache\*"; Flags: ignoreversion recursesubdirs createallsubdirs

[Icons]
Name: "{autoprograms}\Media Creator"; Filename: "{app}\MediaCreator.exe"
Name: "{autodesktop}\Media Creator"; Filename: "{app}\MediaCreator.exe"; Tasks: desktopicon

[Run]
Filename: "{app}\MediaCreator.exe"; Description: "{cm:LaunchProgram,Media Creator}"; Flags: nowait postinstall skipifsilent