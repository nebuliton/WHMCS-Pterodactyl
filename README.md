# Pterodactyl WHMCS Module (Extended)

Advanced WHMCS Module for the [Pterodactyl Panel](https://github.com/pterodactyl/panel/) with live console, power management, and dynamic theme support.

## Features
- **Live Console:** Real-time terminal access via WebSockets.
- **Power Actions:** Start, Restart, Stop, and Kill buttons directly in WHMCS.
- **Dynamic Design:** Automatic Light/Dark mode detection (supports Lagom theme).
- **Internationalization:** Fully localized in **English** and **German**.
- **Management Tabs:** Manage Network (Allocations), Databases, Backups, and Startup variables.
- **Security:** Built-in CSRF protection for all AJAX operations.
- **Centralized Config:** Addon module for global API settings.

## Requirements
- Pterodactyl Panel version 1.0.0 and above.
- WHMCS 8.x (recommended) or WHMCS 7.x.

## Installation

1. **Upload Files:**
   - Move the `modules/addons/pterodactyl/` folder into `<path to whmcs>/modules/addons/`.
   - Move the `modules/servers/pterodactyl/` folder into `<path to whmcs>/modules/servers/`.
2. **Activate Addon:**
   - In WHMCS navigate to **System Settings -> Addon Modules** (WHMCS 8+) or **Setup -> Addon Modules** (WHMCS 7).
   - Activate **Pterodactyl Addon** and configure access permissions.
3. **Configure API Credentials:**
   - Go to **Addons -> Pterodactyl Addon**.
   - Enter your **Pterodactyl URL** (e.g., `https://panel.example.com`).
   - Enter an **Application API Key** (Generated in Admin -> API with all permissions).
   - Enter an **Admin Client API Key** (See "Client API Token" below).
4. **Create Server in WHMCS:**
   - Navigate to **System Settings -> Servers**.
   - Create a new server, choose type **Pterodactyl**.
   - Hostname: Your panel URL (e.g., `panel.example.com`).
   - Password: Your **Application API Key**.
   - Tick **Secure** if using SSL.
5. **Create Server Group:**
   - Create a group and add the Pterodactyl server to it.
6. **Product Configuration:**
   - Navigate to **System Settings -> Products/Services**.
   - Create a new product (Type: Other).
   - In **Module Settings**, choose **Pterodactyl** and select your server group.
   - Fill in the required fields (Nest ID, Egg ID, etc.).

## WebSocket & Console Setup

To enable the live console and real-time status updates:

### 1. Configure Allowed Origins (Wings)
The browser needs permission to connect to the Wings WebSocket from your WHMCS domain.
- Navigate to your **Pterodactyl Panel -> Settings -> General**.
- Find the **Allowed Origins** field.
- Add your WHMCS URL (e.g., `https://whmcs.yourdomain.com`).
- Save the settings. Wings will now accept WebSocket connections from your WHMCS site.

### 2. Create Client API Token
The module uses a Client API token to fetch WebSocket authentication details.
- Log into your Pterodactyl Panel as an **Administrator**.
- Navigate to **Account -> API** (Top right user menu).
- Create a new API Key.
- Copy this key and paste it into the **Pterodactyl Addon settings** in WHMCS (Field: Admin Client API Key) or into the server's **Access Hash** field.

## Overwriting values through configurable options

Overwriting values can be done through either Configurable Options or Custom Fields.

Their name should be exactly what you want to overwrite.
`dedicated_ip` => Will overwrite dedicated_ip if its ticked or not.

**Valid options:**
`server_name, memory, swap, io, cpu, disk, nest_id, egg_id, pack_id, location_id, dedicated_ip, port_range, image, startup, databases, allocations, backups, oom_disabled, username`

**Environment Variables:**
You can also overwrite environment variables. Use the variable name (e.g., `PLAYER_SLOTS`) or the friendly name.
Example: `PLAYER_SLOTS|Player Slots`

## FAQ

### Couldn't find any nodes satisfying the request
This can be caused by: Wrong location ID, not enough disk space/CPU/RAM on nodes, or no allocations matching the provided criteria.

### The server gets assigned to the first/admin user
Ensure you are using the latest version of this module and that the `external_id` is correctly passed to Pterodactyl.

### How to enable module debug log
1. Navigate to **System Logs -> Module Log**.
2. Click **Enable Debug Logging**.
3. Perform the action that failed.
4. Review the logs for "Pterodactyl-WHMCS" entries.

## Credits
- Based on the original work by [death-droid](https://github.com/death-droid) and [Stepas](https://github.com/Stepas).
- Enhanced and modernized for Pterodactyl 1.x and Lagom theme integration.
