<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/xterm/3.14.5/xterm.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/xterm/3.14.5/xterm.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xterm/3.14.5/addons/fit/fit.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    :root {
        --ptero-bg: #f8f9fa;
        --ptero-container-bg: #ffffff;
        --ptero-text: #333333;
        --ptero-text-white: #ffffff;
        --ptero-text-muted: #6c757d;
        --ptero-border: #dee2e6;
        --ptero-input-bg: #ffffff;
        --ptero-input-text: #495057;
        --ptero-input-border: #ced4da;
        --ptero-nav-link-bg: #f1f1f1;
        --ptero-nav-link-active-bg: #ffffff;
        --ptero-terminal-bg: #000000;
        --ptero-table-header-text: #495057;
        --ptero-hr: #dee2e6;
        --ptero-modal-bg: #ffffff;
        --ptero-modal-text: #333333;
    }

    body.lagom-dark, body.theme-dark, body.dark-mode, [data-theme="dark"], .pterodactyl-dark, .pterodactyl-dark-parent, body.pterodactyl-forced-dark {
        --ptero-bg: #1a1a1a;
        --ptero-container-bg: #1a1a1a;
        --ptero-text: #f0f0f0;
        --ptero-text-white: #ffffff;
        --ptero-text-muted: #aaa;
        --ptero-border: #333;
        --ptero-input-bg: #252525;
        --ptero-input-text: #fff;
        --ptero-input-border: #444;
        --ptero-nav-link-bg: #252525;
        --ptero-nav-link-active-bg: #1a1a1a;
        --ptero-terminal-bg: #000000;
        --ptero-table-header-text: #aaa;
        --ptero-hr: #333;
        --ptero-modal-bg: #1a1a1a;
        --ptero-modal-text: #ffffff;
    }

    /* Lagom/WHMCS parent container and global overrides */
    .pterodactyl-dark-parent,
    body.pterodactyl-forced-dark .card,
    body.pterodactyl-forced-dark .section,
    body.pterodactyl-forced-dark .list-group-item,
    body.pterodactyl-forced-dark .panel {
        background-color: var(--ptero-bg) !important;
        background: var(--ptero-bg) !important;
        border-color: var(--ptero-border) !important;
        color: var(--ptero-text) !important;
    }
    .pterodactyl-dark-parent .card-body, 
    .pterodactyl-dark-parent.card-body,
    body.pterodactyl-forced-dark .card-body,
    body.pterodactyl-forced-dark .panel-body {
        background-color: transparent !important;
        background: transparent !important;
        border: none !important;
    }
    body.pterodactyl-forced-dark .nav-tabs {
        border-bottom-color: var(--ptero-border) !important;
    }
    body.pterodactyl-forced-dark .nav-tabs .nav-link {
        color: var(--ptero-text-muted) !important;
    }
    body.pterodactyl-forced-dark .nav-tabs .nav-link.active {
        background-color: var(--ptero-container-bg) !important;
        color: var(--ptero-text) !important;
        border-color: var(--ptero-border) var(--ptero-border) var(--ptero-container-bg) !important;
    }

    .pterodactyl-container {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: var(--ptero-container-bg);
        color: var(--ptero-text);
        padding: 20px;
        border-radius: 15px;
        border: 1px solid var(--ptero-border);
    }
    .terminal-container {
        background: var(--ptero-terminal-bg);
        padding: 10px;
        border-radius: 12px;
        margin-bottom: 10px;
        border: 1px solid var(--ptero-border);
    }
    .server-name {
        color: var(--ptero-text);
        font-weight: 600;
        font-size: 1.2rem;
    }
    .status-dot {
        height: 10px;
        width: 10px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 5px;
    }
    .dot-running { background-color: #28a745; box-shadow: 0 0 8px #28a745; animation: pulse-green 2s infinite; }
    .dot-offline { background-color: #dc3545; box-shadow: 0 0 8px #dc3545; }
    .dot-starting { background-color: #ffc107; box-shadow: 0 0 8px #ffc107; animation: pulse-yellow 2s infinite; }

    @keyframes pulse-green {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(40, 167, 69, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(40, 167, 69, 0); }
    }
    @keyframes pulse-yellow {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(255, 193, 7, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(255, 193, 7, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(255, 193, 7, 0); }
    }
    .status-text {
        font-size: 0.85rem;
        color: var(--ptero-text-muted);
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    .nav-tabs { margin-bottom: 0; border-bottom: 1px solid var(--ptero-border); }
    .nav-tabs .nav-link { color: var(--ptero-text-muted); background-color: var(--ptero-nav-link-bg); border: 1px solid var(--ptero-border); margin-right: 2px; border-top-left-radius: 12px; border-top-right-radius: 12px; }
    .nav-tabs .nav-link:hover { background-color: var(--ptero-border); color: var(--ptero-text); border-color: var(--ptero-border); }
    .nav-tabs .nav-link.active { background-color: var(--ptero-container-bg) !important; color: var(--ptero-text) !important; border-color: var(--ptero-border) var(--ptero-border) var(--ptero-container-bg) !important; }
    
    .tab-content { padding: 20px; background: var(--ptero-container-bg); border: 1px solid var(--ptero-border); border-top: none; border-radius: 0 0 12px 12px; color: var(--ptero-text); }
    
    .btn-panel { background-color: #5c6bc0; color: white; margin-bottom: 20px; }
    .btn-panel:hover { background-color: #3f51b5; color: white; }
    #terminal {
        width: 100%;
        min-height: 400px;
        background: #000;
    }
    .xterm-rows {
        line-height: normal;
    }
    .form-control {
        background-color: var(--ptero-input-bg);
        border: 1px solid var(--ptero-input-border);
        color: var(--ptero-input-text);
        border-radius: 12px;
    }
    .form-control:focus {
        background-color: var(--ptero-input-bg);
        border-color: var(--ptero-border);
        color: var(--ptero-input-text);
        box-shadow: none;
    }
    .table {
        color: var(--ptero-text);
    }
    .table thead th {
        border-bottom: 2px solid var(--ptero-border);
        border-top: none;
        color: var(--ptero-table-header-text);
    }
    .table td {
        border-top: 1px solid var(--ptero-border);
    }
    hr {
        border-top: 1px solid var(--ptero-border);
    }
    code {
        background-color: var(--ptero-input-bg);
        color: #e67e22;
        padding: 2px 4px;
        border-radius: 4px;
    }
    .btn {
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }
    .btn i {
        margin-right: 5px;
    }
    .btn-icon-custom i {
        margin-right: 0 !important;
    }
    h4 { color: var(--ptero-text); margin-bottom: 15px; }
    
    .btn-outline-primary {
        color: #4facfe !important;
        border-color: #4facfe !important;
    }
    .btn-outline-primary:hover {
        background-color: #4facfe !important;
        color: #fff !important;
    }
    
    .btn-start {
        background-color: #2ecc71 !important;
        border: 1px solid #27ae60 !important;
        color: #fff !important;
    }
    .btn-start:hover {
        background-color: #27ae60 !important;
        box-shadow: 0 0 10px rgba(46, 204, 113, 0.4);
    }

    .btn-restart {
        background-color: #f1c40f !important;
        border: 1px solid #f39c12 !important;
        color: #fff !important;
    }
    .btn-restart:hover {
        background-color: #f39c12 !important;
        box-shadow: 0 0 10px rgba(241, 196, 15, 0.4);
    }

    .btn-stop {
        background-color: #e74c3c !important;
        border: 1px solid #c0392b !important;
        color: #fff !important;
    }
    .btn-stop:hover {
        background-color: #c0392b !important;
        box-shadow: 0 0 10px rgba(231, 76, 60, 0.4);
    }

    .btn-kill {
        background-color: #c0392b !important;
        border: 1px solid #a93226 !important;
        color: #fff !important;
    }
    .btn-kill:hover {
        background-color: #a93226 !important;
        box-shadow: 0 0 10px rgba(192, 57, 43, 0.4);
    }
    
    .btn-sm-custom {
        padding: 5px 10px;
        font-size: 12px;
        line-height: 1.5;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 32px;
    }
    .btn-sm-custom i {
        margin-right: 0;
    }
    
    .btn-icon-custom {
        width: 36px;
        height: 36px;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        padding: 0 !important;
        font-size: 15px;
        border-radius: 10px;
        flex-shrink: 0;
    }
    .btn-icon-custom i {
        margin: 0 !important;
        padding: 0 !important;
        line-height: 1 !important;
    }
    
    .btn-lock {
        background-color: #f8f9fa !important;
        border: 1px solid var(--ptero-border) !important;
        color: var(--ptero-text) !important;
    }
    .btn-lock:hover {
        background-color: #e9ecef !important;
    }
    .btn-lock.locked {
        background-color: #6c757d !important;
        border-color: #6c757d !important;
        color: #fff !important;
    }

    .badge-primary-port {
        font-size: 14px;
        padding: 6px 12px;
        border-radius: 12px;
    }

    .db-pw-container {
        display: flex;
        align-items: stretch;
    }
    .db-pw-input {
        border-top-left-radius: 12px !important;
        border-bottom-left-radius: 12px !important;
        border-top-right-radius: 0 !important;
        border-bottom-right-radius: 0 !important;
        background-color: var(--ptero-input-bg) !important;
        border: 1px solid var(--ptero-input-border) !important;
        color: var(--ptero-input-text) !important;
        height: 32px !important;
        padding: 5px 10px !important;
        width: 120px !important;
        font-size: 13px !important;
    }
    .toggle-db-pw {
        border-top-left-radius: 0 !important;
        border-bottom-left-radius: 0 !important;
        border-top-right-radius: 12px !important;
        border-bottom-right-radius: 12px !important;
        border-left: none !important;
        background-color: var(--ptero-input-bg) !important;
        border: 1px solid var(--ptero-input-border) !important;
        color: var(--ptero-input-text) !important;
        height: 32px !important;
        width: 35px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        padding: 0 !important;
        cursor: pointer;
    }
    .toggle-db-pw:hover {
        background-color: var(--ptero-border) !important;
    }
    .toggle-db-pw i {
        color: var(--ptero-input-text) !important;
        font-size: 14px;
        margin: 0 !important;
        line-height: 1 !important;
    }

    #console-input {
        border-top-left-radius: 12px !important;
        border-bottom-left-radius: 12px !important;
        border-top-right-radius: 0 !important;
        border-bottom-right-radius: 0 !important;
    }
    #send-command {
        border-top-left-radius: 0 !important;
        border-bottom-left-radius: 0 !important;
        border-top-right-radius: 12px !important;
        border-bottom-right-radius: 12px !important;
    }
    
    /* Scrollbar (Container only) */
    .pterodactyl-container ::-webkit-scrollbar {
        width: 10px;
        background-color: var(--ptero-bg);
    }
    .pterodactyl-container ::-webkit-scrollbar-track {
        background-color: var(--ptero-bg);
    }
    .pterodactyl-container ::-webkit-scrollbar-thumb {
        background-color: var(--ptero-border);
        border-radius: 5px;
        border: 2px solid var(--ptero-bg);
    }
    .pterodactyl-container ::-webkit-scrollbar-thumb:hover {
        background-color: var(--ptero-text-muted);
    }
    
    /* Firefox Support (Container only) */
    .pterodactyl-container {
        scrollbar-width: thin;
        scrollbar-color: var(--ptero-border) var(--ptero-bg);
    }

    /* Terminal Scrollbar (always dark) */
    .terminal-container ::-webkit-scrollbar {
        background-color: #1a1a1a !important;
    }
    .terminal-container ::-webkit-scrollbar-track {
        background-color: #1a1a1a !important;
    }
    .terminal-container ::-webkit-scrollbar-thumb {
        background-color: #555 !important;
        border: 2px solid #1a1a1a !important;
    }
    .terminal-container ::-webkit-scrollbar-thumb:hover {
        background-color: #777 !important;
    }
    .terminal-container, .terminal-container * {
        scrollbar-color: #555 #1a1a1a !important;
    }
</style>

<div class="pterodactyl-container">
    {if $error}
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i> {$error}
        </div>
    {/if}
    <input type="hidden" id="ptero_token" value="{$token}">
    <div class="row">
        <div class="col-md-12">
            <a href="{$serviceurl}" target="_blank" class="btn btn-panel"><i class="fas fa-external-link-alt"></i> {$lang.to_panel}</a>
        </div>
    </div>

    <ul class="nav nav-tabs" id="pteroTabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="console-tab" data-toggle="tab" href="#console" role="tab">{$lang.console}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="settings-tab" data-toggle="tab" href="#settings" role="tab">{$lang.settings}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="startup-tab" data-toggle="tab" href="#startup" role="tab">{$lang.startup}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="network-tab" data-toggle="tab" href="#network" role="tab">{$lang.network}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="databases-tab" data-toggle="tab" href="#databases" role="tab">{$lang.databases}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="backups-tab" data-toggle="tab" href="#backups" role="tab">{$lang.backups}</a>
        </li>
    </ul>

    <div class="tab-content" id="pteroTabContent">
        <!-- Console -->
        <div class="tab-pane fade show active" id="console" role="tabpanel">
            <div class="server-header d-flex justify-content-between align-items-center mb-3 flex-wrap">
                <div class="mb-2">
                    <h3 class="m-0 server-name">{$servername|default:'Server'}</h3>
                    <div class="status-indicator d-flex align-items-center mt-1">
                        <span id="status-dot" class="status-dot dot-offline"></span>
                        <span id="server-status" class="status-text">{$lang.loading}</span>
                    </div>
                </div>
                <div class="power-actions mb-2">
                    <button class="btn btn-sm btn-start power-action" data-action="start"><i class="fas fa-play"></i> {$lang.start}</button>
                    <button class="btn btn-sm btn-restart power-action" data-action="restart"><i class="fas fa-sync-alt"></i> {$lang.restart}</button>
                    <button class="btn btn-sm btn-stop power-action" data-action="stop"><i class="fas fa-stop"></i> {$lang.stop}</button>
                    <button class="btn btn-sm btn-kill power-action" data-action="kill"><i class="fas fa-bolt"></i> {$lang.kill}</button>
                </div>
            </div>
            <div class="terminal-container">
                <div id="terminal"></div>
            </div>
            <div class="input-group">
                <input type="text" id="console-input" class="form-control" placeholder="{$lang.send_command}">
                <div class="input-group-append">
                    <button class="btn btn-primary" id="send-command">{$lang.send}</button>
                </div>
            </div>
        </div>

        <!-- Settings -->
        <div class="tab-pane fade" id="settings" role="tabpanel">
            <h4>{$lang.server_settings}</h4>
            <div class="form-group mb-3">
                <label>{$lang.server_name}</label>
                <input type="text" id="settings-name" class="form-control" placeholder="{$lang.server_name}">
            </div>
            <button class="btn btn-primary" id="update-settings">{$lang.save}</button>
            <hr>
            <h4>{$lang.actions}</h4>
            <button class="btn btn-warning" id="reinstall-server">{$lang.reinstall}</button>
        </div>

        <!-- Startup -->
        <div class="tab-pane fade" id="startup" role="tabpanel">
            <h4>{$lang.startup_settings}</h4>
            <div id="startup-vars">{$lang.loading}</div>
        </div>

        <!-- Netzwerk -->
        <div class="tab-pane fade" id="network" role="tabpanel">
            <div class="row">
                <div class="col-md-8"><h4>{$lang.network_allocations}</h4></div>
                <div class="col-md-4 text-right text-end">
                    <button class="btn btn-primary btn-icon-custom" id="refresh-network-btn" onclick="loadNetwork()"><i class="fas fa-sync"></i></button>
                    <button class="btn btn-success btn-sm-custom" id="create-allocation-btn">{$lang.new_port}</button>
                </div>
            </div>
            <div id="allocation-limit-info" class="mb-3 text-muted small"></div>
            <table class="table">
                <thead>
                    <tr>
                        <th>{$lang.ip_address}</th>
                        <th>{$lang.port}</th>
                        <th>{$lang.alias}</th>
                        <th>{$lang.primary_actions}</th>
                    </tr>
                </thead>
                <tbody id="network-list"></tbody>
            </table>
        </div>

        <!-- Datenbanken -->
        <div class="tab-pane fade" id="databases" role="tabpanel">
            <div class="row">
                <div class="col-md-8"><h4>{$lang.databases}</h4></div>
                <div class="col-md-4 text-right text-end">
                    <button class="btn btn-success btn-sm" id="show-create-db">{$lang.new_database}</button>
                </div>
            </div>
            <div id="create-db-form" style="display:none;" class="mb-4 p-3 border rounded">
                <h5>{$lang.create_database}</h5>
                <div class="form-group mb-2">
                    <input type="text" id="new-db-name" class="form-control" placeholder="{$lang.name}">
                </div>
                <div class="form-group mb-2">
                    <input type="text" id="new-db-remote" class="form-control" placeholder="{$lang.remote}" value="%">
                </div>
                <button class="btn btn-primary btn-sm" id="confirm-create-db">{$lang.create}</button>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>{$lang.database}</th>
                        <th>{$lang.user}</th>
                        <th>{$lang.host}</th>
                        <th>{$lang.password}</th>
                        <th>{$lang.actions}</th>
                    </tr>
                </thead>
                <tbody id="databases-list"></tbody>
            </table>
        </div>

        <!-- Backups -->
        <div class="tab-pane fade" id="backups" role="tabpanel">
            <div class="row">
                <div class="col-md-8"><h4>{$lang.backups}</h4></div>
                <div class="col-md-4 text-right text-end">
                    <button class="btn btn-success btn-sm" id="create-backup-btn">{$lang.backup_create}</button>
                </div>
            </div>
            <div id="backup-limit-info" class="mb-3 text-muted small"></div>
            <table class="table">
                <thead>
                    <tr>
                        <th>{$lang.name}</th>
                        <th>{$lang.size}</th>
                        <th>{$lang.status}</th>
                        <th>{$lang.date}</th>
                        <th>{$lang.actions}</th>
                    </tr>
                </thead>
                <tbody id="backups-list"></tbody>
            </table>
        </div>
    </div>
</div>

{literal}
<script>
$(document).ready(function() {
    // --- CONFIGURATION ---
    // Default theme if no detection works ('light' or 'dark')
    const pteroDefaultTheme = 'light';
    // ---------------------

    const checkDarkMode = () => {
        const h = document.documentElement;
        const b = document.body;
        const classes = (b.className || "") + " " + (h.className || "");
        let isDarkNow = classes.match(/\b(lagom2?-dark|lagom-dark|theme-dark|dark-mode)\b/) !== null;
        
        if (!isDarkNow) {
            if ((b.getAttribute("data-theme") === "dark") || (h.getAttribute("data-theme") === "dark")) {
                isDarkNow = true;
            }
        }

        const lsKeys = ['lagom-theme', 'lagom_mode', 'lagom_color_scheme', 'color-scheme', 'color-mode', 'theme'];
        for (const key of lsKeys) {
            if (isDarkNow) break;
            try {
                const val = localStorage.getItem(key);
                if (val && /dark/i.test(val)) isDarkNow = true;
            } catch (e) {}
        }

        // Default logic: If nothing was detected
        if (!isDarkNow) {
            const isLightDetected = classes.match(/\b(lagom2?-light|lagom-light|theme-light|light-mode)\b/) !== null
                || (b.getAttribute("data-theme") === "light")
                || (h.getAttribute("data-theme") === "light");
            
            if (!isLightDetected && pteroDefaultTheme === 'dark') {
                isDarkNow = true;
            }
        }

        if (isDarkNow) {
            $('body').addClass('pterodactyl-forced-dark');
            $('.pterodactyl-container').addClass('pterodactyl-dark');
            // Try to find and darken the parent WHMCS card/container
            $('.pterodactyl-container').closest('.card').addClass('pterodactyl-dark-parent');
            $('.pterodactyl-container').closest('.card-body').addClass('pterodactyl-dark-parent');
            $('.pterodactyl-container').closest('.section').addClass('pterodactyl-dark-parent');
        } else {
            $('body').removeClass('pterodactyl-forced-dark');
            $('.pterodactyl-container').removeClass('pterodactyl-dark');
            $('.pterodactyl-container').closest('.card').removeClass('pterodactyl-dark-parent');
            $('.pterodactyl-container').closest('.card-body').removeClass('pterodactyl-dark-parent');
            $('.pterodactyl-container').closest('.section').removeClass('pterodactyl-dark-parent');
        }
        return isDarkNow;
    };
    
    // Initial check
    const getSwalOptions = (overrides = {}) => {
        const isDarkNow = checkDarkMode();
        return Object.assign({
            background: isDarkNow ? '#1a1a1a' : '#fff',
            color: isDarkNow ? '#fff' : '#333',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
        }, overrides);
    };

    const Toast = {
        fire: (options) => {
            const isDarkNow = checkDarkMode();
            return Swal.fire(Object.assign({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                background: isDarkNow ? '#252525' : '#fff',
                color: isDarkNow ? '#fff' : '#333',
                width: '280px',
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            }, options));
        }
    };

    let isDark = checkDarkMode();

    const lang = {/literal}{$lang|json_encode|default:'{}'}{literal};
    const identifier = '{/literal}{$identifier}{literal}';
    const pteroToken = $('#ptero_token').val();
    let socket = null;
    Terminal.applyAddon(fit);
    
    let term = new Terminal({
        cursorBlink: true,
        fontSize: 12,
        cols: 100,
        rows: 25,
        convertEol: true,
        theme: {
            background: '#000000',
            foreground: '#ffffff'
        }
    });
    term.open(document.getElementById('terminal'));

    // Regular check for dynamic changes (e.g. Lagom Switcher without reload)
    setInterval(checkDarkMode, 2000);

    setTimeout(function() {
        term.fit();
    }, 100);

    $(window).resize(function() {
        term.fit();
    });

    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        if ($(e.target).attr('id') === 'console-tab') {
            term.fit();
        }
    });

    function logToTerm(msg) {
        term.writeln('\x1b[1;30m[WHMCS]\x1b[0m ' + msg);
    }

    function connectWebsocket() {
        logToTerm(lang.ws_connecting);
        $.getJSON(window.location.href, {modaction: 'get_websocket', token: pteroToken}, function(data) {
            if (data.data && data.data.socket) {
                const wsUrl = data.data.socket;
                const token = data.data.token;
                
                socket = new WebSocket(wsUrl);
                
                socket.onopen = function() {
                    logToTerm('\x1b[1;32m' + lang.ws_connected + '\x1b[0m');
                    socket.send(JSON.stringify({event: 'auth', args: [token]}));
                };
                
                socket.onmessage = function(e) {
                    const msg = JSON.parse(e.data);
                    switch (msg.event) {
                        case 'status':
                            updateStatusBadge(msg.args[0]);
                            break;
                        case 'console output':
                            // Ensure each message gets its own line
                            term.writeln(msg.args[0]);
                            break;
                        case 'stats':
                            // Stats can be handled here if needed
                            break;
                        case 'token expired':
                            connectWebsocket();
                            break;
                        case 'auth success':
                            // Optional: request logs or just wait for messages
                            break;
                    }
                };
                
                socket.onclose = function() {
                    logToTerm('\x1b[1;31m' + lang.ws_disconnected + '\x1b[0m');
                    setTimeout(connectWebsocket, 5000);
                };
                
                socket.onerror = function(err) {
                    logToTerm('\x1b[1;31m' + lang.ws_error + '\x1b[0m');
                };
            } else {
                logToTerm('\x1b[1;31m' + lang.ws_error_data + '\x1b[0m');
            }
        }).fail(function(jqXHR, textStatus, errorThrown) {
            logToTerm('\x1b[1;31m' + lang.ws_error + ' (AJAX Fail: ' + textStatus + ' ' + errorThrown + ')\x1b[0m');
            if (jqXHR.status === 403) {
                logToTerm('\x1b[1;31mCSRF Token Validation Failed. Please reload the page.\x1b[0m');
            }
        });
    }

    function updateStatusBadge(status) {
        const badge = $('#server-status');
        const dot = $('#status-dot');
        dot.removeClass('dot-running dot-offline dot-starting');
        
        switch (status) {
            case 'running':
                badge.html(lang.status_online);
                dot.addClass('dot-running');
                break;
            case 'offline':
                badge.html(lang.status_offline);
                dot.addClass('dot-offline');
                break;
            case 'starting':
                badge.html(lang.status_starting);
                dot.addClass('dot-starting');
                break;
            case 'stopping':
                badge.html(lang.status_stopping);
                dot.addClass('dot-starting');
                break;
            default:
                badge.text(status);
                dot.addClass('dot-offline');
        }
    }

    if (identifier) {
        connectWebsocket();
    } else {
        logToTerm('\x1b[1;31m' + (lang.server_not_found || 'Server not found.') + '\x1b[0m');
        $('#server-status').html(lang.status_offline);
    }

    // Console Input
    $('#send-command').click(function() {
        const cmd = $('#console-input').val();
        if (cmd) {
            $.post(window.location.href, {modaction: 'send_command', command: cmd, token: pteroToken}, function() {
                $('#console-input').val('');
            });
        }
    });

    $('#console-input').keypress(function(e) {
        if(e.which == 13) $('#send-command').click();
    });

    // Power Actions
    $('.power-action').click(function() {
        const action = $(this).data('action');
        $.post(window.location.href, {modaction: 'power_action', signal: action, token: pteroToken});
    });

    // Tab loading
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        const target = $(e.target).attr("href");
        if (target === '#startup') loadStartup();
        if (target === '#network') loadNetwork();
        if (target === '#databases') loadDatabases();
        if (target === '#backups') loadBackups();
    });

    function loadStartup() {
        $('#startup-vars').html(lang.loading);
        $.getJSON(window.location.href, {modaction: 'get_startup', token: pteroToken, _: new Date().getTime()}, function(data) {
            if (data.data) {
                let html = '';
                data.data.forEach(function(v) {
                    html += `<div class="form-group mb-3">
                        <label><strong>${v.attributes.name}</strong> (${v.attributes.env_variable})</label>
                        <input type="text" class="form-control startup-var-input" data-key="${v.attributes.env_variable}" value="${v.attributes.server_value || v.attributes.default_value}">
                        <small class="text-muted">${v.attributes.description}</small>
                    </div>`;
                });
                html += '<button class="btn btn-primary" id="save-startup">' + lang.save + '</button>';
                $('#startup-vars').html(html);

                $('#save-startup').click(function() {
                    const promises = [];
                    $('.startup-var-input').each(function() {
                        const key = $(this).data('key');
                        const val = $(this).val();
                        promises.push($.post(window.location.href, {modaction: 'update_startup', key: key, value: val, token: pteroToken}));
                    });
                    
                    Promise.all(promises).then(() => {
                        Toast.fire({
                            icon: 'success',
                            title: lang.toast_startup_updated
                        });
                    }).catch(() => {
                        Toast.fire({
                            icon: 'error',
                            title: lang.toast_startup_error
                        });
                    });
                });
            }
        });
    }

    function loadNetwork() {
        $('#refresh-network-btn i').addClass('fa-spin');
        // Zuerst Server-Limits laden
        $.getJSON(window.location.href, {modaction: 'get_server', token: pteroToken, _: new Date().getTime()}, function(serverData) {
            const limits = serverData.attributes.feature_limits;
            const allocationLimit = limits.allocations;
            
            $.getJSON(window.location.href, {modaction: 'get_network', token: pteroToken, _: new Date().getTime()}, function(data) {
                $('#refresh-network-btn i').removeClass('fa-spin');
                if (data.data) {
                    let html = '';
                    const currentCount = data.data.length;
                    
                    $('#allocation-limit-info').text(`${lang.ports_used}: ${currentCount} / ${allocationLimit || lang.unlimited}`);
                    
                    if (allocationLimit > 0 && currentCount >= allocationLimit) {
                        $('#create-allocation-btn').prop('disabled', true).addClass('btn-secondary').removeClass('btn-success');
                    } else {
                        $('#create-allocation-btn').prop('disabled', false).addClass('btn-success').removeClass('btn-secondary');
                    }

                    data.data.sort((a, b) => b.attributes.is_default - a.attributes.is_default);
                    data.data.forEach(function(a) {
                        const attr = a.attributes;
                        html += `<tr>
                            <td>${attr.ip}</td>
                            <td>${attr.port}</td>
                            <td>${attr.ip_alias || '-'}</td>
                            <td>
                                ${attr.is_default ? `<span class="badge badge-success badge-primary-port">${lang.yes}</span>` : `
                                    <button class="btn btn-sm btn-outline-primary set-primary mr-1 mb-1" data-id="${attr.id}">${lang.set_primary}</button>
                                    <button class="btn btn-icon-custom btn-danger delete-allocation mb-1" data-id="${attr.id}"><i class="fas fa-trash"></i></button>
                                `}
                            </td>
                        </tr>`;
                    });
                    $('#network-list').html(html);
                    $('.set-primary').off('click').click(function() {
                        const id = $(this).data('id');
                        $.post(window.location.href, {modaction: 'set_primary_allocation', allocation_id: id, token: pteroToken}, function() {
                            loadNetwork();
                            Toast.fire({ icon: 'success', title: lang.toast_primary_changed });
                        });
                    });
                    $('.delete-allocation').off('click').click(function() {
                        const id = $(this).data('id');
                        Swal.fire(getSwalOptions({
                            title: lang.delete_allocation_confirm,
                            html: lang.delete_allocation_body,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: lang.yes_delete,
                            cancelButtonText: lang.cancel
                        })).then((result) => {
                            if (result.isConfirmed) {
                                $.post(window.location.href, {modaction: 'delete_allocation', allocation_id: id, token: pteroToken}, function() {
                                    loadNetwork();
                                    Toast.fire({ icon: 'success', title: lang.toast_port_deleted });
                                });
                            }
                        });
                    });
                }
            });
        });
    }

    $('#create-allocation-btn').click(function() {
        $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>...');
        $.post(window.location.href, {modaction: 'create_allocation', token: pteroToken}, function(response) {
            $('#create-allocation-btn').html(lang.new_port);
            let data = {};
            try {
                data = typeof response === 'string' ? JSON.parse(response) : response;
            } catch (e) {
                data = {};
            }
            
            if (data.errors) {
                Swal.fire(getSwalOptions({
                    icon: 'error',
                    title: lang.toast_error,
                    text: data.errors[0].detail
                }));
            } else {
                Toast.fire({
                    icon: 'success',
                    title: lang.toast_port_created
                });
                loadNetwork();
                setTimeout(loadNetwork, 1000);
                setTimeout(loadNetwork, 3000);
                setTimeout(loadNetwork, 5000);
            }
        }).fail(function() {
            $('#create-allocation-btn').html(lang.new_port);
            $('#refresh-network-btn i').removeClass('fa-spin');
            Toast.fire({ icon: 'error', title: lang.toast_error });
        });
    });

    function loadDatabases() {
        $.getJSON(window.location.href, {modaction: 'get_databases', token: pteroToken, _: new Date().getTime()}, function(data) {
            if (data.data) {
                let html = '';
                data.data.forEach(function(d) {
                    const attr = d.attributes;
                    const pw = attr.relationships && attr.relationships.password ? attr.relationships.password.attributes.password : '';
                    html += `<tr>
                        <td>${attr.name}</td>
                        <td>${attr.username}</td>
                        <td>${attr.host.address}:${attr.host.port}</td>
                        <td>
                            <div class="db-pw-container">
                                <input type="password" class="db-pw-input" value="${pw}" readonly>
                                <button class="toggle-db-pw" type="button"><i class="fas fa-eye"></i></button>
                            </div>
                            <button class="btn btn-sm btn-link rotate-pw p-0 mt-1" data-id="${attr.id}" title="${lang.rotate_password}"><i class="fas fa-sync"></i></button>
                        </td>
                        <td><button class="btn btn-sm btn-danger delete-db" data-id="${attr.id}">${lang.delete}</button></td>
                    </tr>`;
                });
                $('#databases-list').html(html);

                $('.toggle-db-pw').click(function() {
                    const input = $(this).closest('.db-pw-container').find('.db-pw-input');
                    const icon = $(this).find('i');
                    if (input.attr('type') === 'password') {
                        input.attr('type', 'text');
                        icon.removeClass('fa-eye').addClass('fa-eye-slash');
                    } else {
                        input.attr('type', 'password');
                        icon.removeClass('fa-eye-slash').addClass('fa-eye');
                    }
                });
                
                $('.delete-db').off('click').click(function() {
                    const id = $(this).data('id');
                    Swal.fire(getSwalOptions({
                        title: lang.delete_db_confirm,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: lang.yes_delete,
                        cancelButtonText: lang.cancel
                    })).then((result) => {
                        if (result.isConfirmed) {
                            $.post(window.location.href, {modaction: 'delete_database', db_id: id, token: pteroToken}, function() {
                                loadDatabases();
                                Toast.fire({ icon: 'success', title: lang.toast_db_deleted });
                            });
                        }
                    });
                });
                $('.rotate-pw').off('click').click(function() {
                    const id = $(this).data('id');
                    $.post(window.location.href, {modaction: 'rotate_db_password', db_id: id, token: pteroToken}, function() {
                        loadDatabases();
                        Toast.fire({ icon: 'success', title: lang.toast_password_rotated });
                    });
                });
            }
        });
    }

    function loadBackups() {
        $.getJSON(window.location.href, {modaction: 'get_server', token: pteroToken, _: new Date().getTime()}, function(serverData) {
            const backupLimit = serverData.attributes.feature_limits.backups;
            
            $.getJSON(window.location.href, {modaction: 'get_backups', token: pteroToken, _: new Date().getTime()}, function(data) {
                if (data.data) {
                    let html = '';
                    const currentCount = data.data.length;
                    $('#backup-limit-info').text(`${lang.backups_used}: ${currentCount} / ${backupLimit || lang.unlimited}`);
                    
                    if (backupLimit > 0 && currentCount >= backupLimit) {
                        $('#create-backup-btn').prop('disabled', true).addClass('btn-secondary').removeClass('btn-success');
                    } else {
                        $('#create-backup-btn').prop('disabled', false).addClass('btn-success').removeClass('btn-secondary');
                    }

                    data.data.forEach(function(b) {
                        const attr = b.attributes;
                        const date = new Date(attr.created_at).toLocaleString();
                        const size = (attr.bytes / 1024 / 1024).toFixed(2) + ' MB';
                        const isLocked = attr.is_locked;
                        
                        html += `<tr>
                            <td>${attr.name}</td>
                            <td>${attr.bytes > 0 ? size : lang.calculating}</td>
                            <td>${attr.completed_at ? `<span class="badge badge-success">${lang.finished}</span>` : `<span class="badge badge-warning">${lang.running}</span>`}</td>
                            <td>${date}</td>
                            <td>
                                <div style="display: flex; gap: 5px;">
                                    <button class="btn btn-sm btn-info btn-icon-custom download-backup" data-id="${attr.uuid}" title="Download"><i class="fas fa-download"></i></button>
                                    <button class="btn btn-sm btn-warning btn-icon-custom restore-backup" data-id="${attr.uuid}" title="Restore"><i class="fas fa-undo"></i></button>
                                    <button class="btn btn-sm ${isLocked ? 'btn-lock locked' : 'btn-lock'} btn-icon-custom lock-backup" data-id="${attr.uuid}" title="${isLocked ? lang.unlock : lang.lock}"><i class="fas ${isLocked ? 'fa-lock' : 'fa-lock-open'}"></i></button>
                                    <button class="btn btn-sm btn-danger btn-icon-custom delete-backup" data-id="${attr.uuid}" title="${lang.delete}"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>`;
                    });
                    $('#backups-list').html(html);

                    $('.download-backup').off('click').click(function() {
                        const id = $(this).data('id');
                        $.getJSON(window.location.href, {modaction: 'download_backup', backup_id: id, token: pteroToken}, function(data) {
                            if (data.attributes && data.attributes.url) {
                                window.open(data.attributes.url, '_blank');
                            }
                        });
                    });

                    $('.restore-backup').off('click').click(function() {
                        const id = $(this).data('id');
                        Swal.fire(getSwalOptions({
                            title: lang.restore_backup_confirm,
                            html: lang.restore_backup_body,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#f39c12',
                            confirmButtonText: lang.yes_restore,
                            cancelButtonText: lang.cancel
                        })).then((result) => {
                            if (result.isConfirmed) {
                                $.post(window.location.href, {modaction: 'restore_backup', backup_id: id, token: pteroToken}, function() {
                                    Toast.fire({ icon: 'info', title: lang.toast_restore_started });
                                });
                            }
                        });
                    });

                    $('.lock-backup').off('click').click(function() {
                        const id = $(this).data('id');
                        $.post(window.location.href, {modaction: 'lock_backup', backup_id: id, token: pteroToken}, function() {
                            loadBackups();
                        });
                    });

                    $('.delete-backup').off('click').click(function() {
                        const id = $(this).data('id');
                        Swal.fire(getSwalOptions({
                            title: lang.delete_backup_confirm,
                            html: lang.delete_backup_body,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: lang.yes_delete,
                            cancelButtonText: lang.cancel
                        })).then((result) => {
                            if (result.isConfirmed) {
                                $.post(window.location.href, {modaction: 'delete_backup', backup_id: id, token: pteroToken}, function() {
                                    loadBackups();
                                    Toast.fire({ icon: 'success', title: lang.toast_port_deleted });
                                });
                            }
                        });
                    });
                }
            });
        });
    }

    $('#create-backup-btn').click(function() {
        Swal.fire(getSwalOptions({
            title: lang.backup_create,
            input: 'text',
            inputLabel: lang.backup_name_optional,
            inputPlaceholder: lang.backup_placeholder,
            showCancelButton: true,
            confirmButtonText: lang.create,
            cancelButtonText: lang.cancel,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            didOpen: () => {
                const input = Swal.getInput();
                const isDarkNow = checkDarkMode();
                if (input) {
                    input.style.backgroundColor = isDarkNow ? '#252525' : '#fff';
                    input.style.color = isDarkNow ? '#fff' : '#333';
                    input.style.borderColor = isDarkNow ? '#444' : '#ccc';
                    input.style.boxShadow = 'none';
                    input.style.width = 'calc(100% - 40px)';
                    input.style.boxSizing = 'border-box';
                    input.style.margin = '15px auto';
                    input.style.display = 'block';
                }
                const label = document.querySelector('.swal2-input-label');
                if (label) {
                    label.style.color = isDarkNow ? '#fff' : '#333';
                }
            }
        })).then((result) => {
            if (result.isConfirmed) {
                $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>...');
                $.post(window.location.href, {modaction: 'create_backup', name: result.value, token: pteroToken}, function() {
                    $('#create-backup-btn').prop('disabled', false).html(lang.backup_create);
                    loadBackups();
                    Toast.fire({ icon: 'success', title: lang.toast_backup_started });
                });
            }
        });
    });

    $('#show-create-db').click(function() {
        $('#create-db-form').toggle();
    });

    $('#confirm-create-db').click(function() {
        const name = $('#new-db-name').val();
        const remote = $('#new-db-remote').val();
        if (name) {
            $.post(window.location.href, {modaction: 'create_database', database: name, remote: remote, token: pteroToken}, function() {
                $('#new-db-name').val('');
                $('#create-db-form').hide();
                loadDatabases();
                Toast.fire({ icon: 'success', title: lang.toast_db_created });
            });
        }
    });

    $('#update-settings').click(function() {
        const name = $('#settings-name').val();
        if (name) {
            $.post(window.location.href, {modaction: 'update_settings', name: name, token: pteroToken}, function() {
                Toast.fire({
                    icon: 'success',
                    title: lang.toast_settings_saved
                });
            });
        }
    });

    $('#reinstall-server').click(function() {
        Swal.fire(getSwalOptions({
            title: lang.reinstall_confirm_title,
            html: lang.reinstall_confirm_body,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#f39c12',
            confirmButtonText: lang.reinstall_confirm_btn,
            cancelButtonText: lang.cancel
        })).then((result) => {
            if (result.isConfirmed) {
                $.post(window.location.href, {modaction: 'reinstall_server', token: pteroToken}, function() {
                    Toast.fire({
                        icon: 'info',
                        title: lang.toast_reinstall_started
                    });
                });
            }
        });
    });
});
</script>
{/literal}
