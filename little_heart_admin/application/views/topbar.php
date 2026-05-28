
        
        
        <!-- TOPBAR -->
        <header class="topbar">
            <button class="sidebar-toggle" id="openSidebar">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                    stroke-linecap="round" stroke-linejoin="round">
                    <line x1="3" y1="12" x2="21" y2="12" />
                    <line x1="3" y1="6" x2="21" y2="6" />
                    <line x1="3" y1="18" x2="21" y2="18" />
                </svg>
            </button>
            <div style="display:flex;flex-direction:column;gap:1px;">
                <div class="topbar-breadcrumb">
                    <span class="breadcrumb-home">Dashboard</span>
                    <span class="breadcrumb-sep"></span>
                </div>
            </div>

            <div class="topbar-right">
         
                <div class="topbar-notify" id="notifyTrigger">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" />
                        <path d="M13.73 21a2 2 0 0 1-3.46 0" />
                    </svg>
                    <span class="notify-dot"></span>

                    <!-- Dropdown Content -->
                    <div class="notify-dropdown" id="notifyBox">
                        <!-- <div
                            style="font-size:12px; font-weight:800; color:var(--g700); margin-bottom:12px; padding-left:5px; text-transform:uppercase; letter-spacing:0.5px;">
                            Notifications</div>
                       
                         -->
                        <div style="padding:10px 0 0; text-align:center; border-top:1px solid var(--g50);">
                            <a href="#"
                                style="font-size:11px; font-weight:700; color:var(--g600); text-decoration:none;">View
                                All Notifications</a>
                        </div>
                    </div>
                </div>
            </div>
        </header>
