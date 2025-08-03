<aside
  :class="sidebarToggle ? 'translate-x-0' : '-translate-x-full'"
  class="
  sidebar fixed top-0 left-0 z-40 flex h-screen w-[260px] flex-col overflow-y-auto border-r border-gray-200 bg-white transition-all duration-300 lg:static lg:translate-x-0 dark:border-gray-700/80 dark:bg-black -translate-x-full
  @click.outside="sidebarToggle = false"
  x-data="{ 
    closeAllDropdowns() {
      document.querySelectorAll('[x-data]').forEach(el => { 
        if (el.__x && el.__x.$data.open) {
          el.__x.$data.open = false;
        }
      });
    }
  }"
  @menu-item-clicked.window="closeAllDropdowns()"
>
  <!-- SIDEBAR HEADER -->
  <div class="flex items-center justify-between gap-2 px-6 py-5.5 lg:py-6.5">
    <a href="{{ route('dashboard.index') }}" class="inline-flex items-center gap-2" @click="closeAllDropdowns()">
      <img src="{{ gym()->logo_url }}" class="w-10 h-10 rounded-md border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" alt="Logo" />
      <span class="text-gray-900 text-lg font-bold dark:text-white">{{ gym()->name }}</span>
    </a>

    <button
      class="block lg:hidden"
      @click.stop="sidebarToggle = !sidebarToggle"
    >
      <svg
        class="fill-current"
        width="20"
        height="18"
        viewBox="0 0 20 18"
        fill="none"
        xmlns="http://www.w3.org/2000/svg"
      >
        <path
          d="M19 8.175H2.98748L9.36248 1.6875C9.69998 1.35 9.69998 0.825 9.36248 0.4875C9.02498 0.15 8.49998 0.15 8.16248 0.4875L0.399976 8.3625C0.0624756 8.7 0.0624756 9.225 0.399976 9.5625L8.16248 17.4375C8.31248 17.5875 8.53748 17.7 8.76248 17.7C8.98748 17.7 9.17498 17.625 9.36248 17.475C9.69998 17.1375 9.69998 16.6125 9.36248 16.275L3.02498 9.8625H19C19.45 9.8625 19.825 9.4875 19.825 9.0375C19.825 8.55 19.45 8.175 19 8.175Z"
          fill=""
        />
      </svg>
    </button>
  </div>
  <!-- SIDEBAR HEADER -->

  <div
    class="no-scrollbar flex flex-col overflow-y-auto duration-300 ease-linear"
  >
    <!-- Sidebar Menu -->
    <nav
      class="px-2 py-4"
      x-data="{ selected: null }"
      @click.menu-item.window="selected = $event.detail.title; document.querySelectorAll('[x-data]').forEach(el => { if (el.__x && el.__x.$data.open && el.__x.$data.title !== $event.detail.title) el.__x.$data.open = false; });"
      x-init="$watch('selected', value => { if (value) { document.querySelectorAll('[x-data]').forEach(el => { if (el.__x && el.__x.$data.title === value) el.__x.$data.open = true; }); } })"
    >
      <!-- Menu Group -->
      <div>
        <ul class="mb-6 flex text-sm flex-col gap-3 text-white">
         
            @if(has_module_access('dashboard') && auth()->user()->getCachedPermissions()->contains('view_dashboard'))
            <livewire:sidebar-menu-item
              :title="__('sidebar.dashboard.title')"
              :icon="<<<'SVG'
                  <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-speedometer2' viewBox='0 0 16 16'>
                      <path d='M8 4a.5.5 0 0 1 .5.5V6a.5.5 0 0 1-1 0V4.5A.5.5 0 0 1 8 4M3.732 5.732a.5.5 0 0 1 .707 0l.915.914a.5.5 0 1 1-.708.708l-.914-.915a.5.5 0 0 1 0-.707M2 10a.5.5 0 0 1 .5-.5h1.586a.5.5 0 0 1 0 1H2.5A.5.5 0 0 1 2 10m9.5 0a.5.5 0 0 1 .5-.5h1.5a.5.5 0 0 1 0 1H12a.5.5 0 0 1-.5-.5m.754-4.246a.39.39 0 0 0-.527-.02L7.547 9.31a.91.91 0 1 0 1.302 1.258l3.434-4.297a.39.39 0 0 0-.029-.518z'/>
                      <path fill-rule='evenodd' d='M0 10a8 8 0 1 1 15.547 2.661c-.442 1.253-1.845 1.602-2.932 1.25C11.309 13.488 9.475 13 8 13c-1.474 0-3.31.488-4.615.911-1.087.352-2.49.003-2.932-1.25A8 8 0 0 1 0 10m8-7a7 7 0 0 0-6.603 9.329c.203.575.923.876 1.68.63C4.397 12.533 6.358 12 8 12s3.604.532 4.923.96c.757.245 1.477-.056 1.68-.631A7 7 0 0 0 8 3'/>
                  </svg>
                SVG"
              link="dashboard.index"
              @click="$dispatch('menu-item-clicked')"
            />
            @endif
            @if(has_module_access('services') && auth()->user()->getCachedPermissions()->contains('view_services'))
            <livewire:sidebar-menu-item
              :title="__('sidebar.services.title')"
              :icon="<<<'SVG'
                  <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-columns-gap' viewBox='0 0 16 16'>
                    <path d='M6 1v3H1V1zM1 0a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h5a1 1 0 0 0 1-1V1a1 1 0 0 0-1-1zm14 12v3h-5v-3zm-5-1a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h5a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1zM6 8v7H1V8zM1 7a1 1 0 0 0-1 1v7a1 1 0 0 0 1 1h5a1 1 0 0 0 1-1V8a1 1 0 0 0-1-1zm14-6v7h-5V1zm-5-1a1 1 0 0 0-1 1v7a1 1 0 0 0 1 1h5a1 1 0 0 0 1-1V1a1 1 0 0 0-1-1z'/>
                  </svg>
                SVG"
              link="services.index"
              @click="$dispatch('menu-item-clicked')"
            />
            @endif

            @if((has_module_access('activity_classes') && auth()->user()->getCachedPermissions()->contains('view_activity_classes')) || 
                (has_module_access('staff_schedule') && auth()->user()->getCachedPermissions()->contains('view_staff_schedule')))
              @php
                $dropdownItems = [];
                if (has_module_access('activity_classes') && auth()->user()->getCachedPermissions()->contains('view_activity_classes')) {
                  $dropdownItems[] = ['id' => 'manage_activity', 'title' => __('sidebar.manage_activity.title'), 'link' => 'activity-classes.index'];
                }
                if (has_module_access('staff_schedule') && auth()->user()->getCachedPermissions()->contains('view_staff_schedule')) {
                  $dropdownItems[] = ['id' => 'staff_schedule', 'title' => __('sidebar.staff_schedule.title'), 'link' => 'staff-schedule.index'];
                }
              @endphp
              <livewire:sidebar-menu-item 
                :title="__('sidebar.activity_classes.title')"
                :has-dropdown="true"
                :dropdown-items=$dropdownItems
                :icon="<<<'SVG'
                  <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-activity' viewBox='0 0 16 16'>
                      <path fill-rule='evenodd' d='M6 2a.5.5 0 0 1 .47.33L10 12.036l1.53-4.208A.5.5 0 0 1 12 7.5h3.5a.5.5 0 0 1 0 1h-3.15l-1.88 5.17a.5.5 0 0 1-.94 0L6 3.964 4.47 8.171A.5.5 0 0 1 4 8.5H.5a.5.5 0 0 1 0-1h3.15l1.88-5.17A.5.5 0 0 1 6 2'/>
                  </svg>
                SVG"
              />
            @endif

            @if((has_module_access('memberships') && auth()->user()->getCachedPermissions()->contains('view_memberships')) || 
                (has_module_access('user_memberships') && auth()->user()->getCachedPermissions()->contains('view_user_memberships')))
              @php
                $dropdownItems = [];
                if (has_module_access('memberships') && auth()->user()->getCachedPermissions()->contains('view_memberships')) {
                  $dropdownItems[] = ['id' => 'membership_list', 'title' => __('sidebar.membership_list.title'), 'link' => 'memberships.index'];
                }
                if (has_module_access('memberships') && auth()->user()->getCachedPermissions()->contains('view_user_memberships')) {
                  $dropdownItems[] = ['id' => 'user_memberships', 'title' => __('sidebar.user_memberships.title'), 'link' => 'user-memberships.index'];
                }
              @endphp
              <livewire:sidebar-menu-item 
                :title="__('sidebar.membership_management.title')"
                :has-dropdown="true"
                :dropdown-items=$dropdownItems
                :icon="<<<'SVG'
                  <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-award' viewBox='0 0 16 16'>
                    <path d='M9.669.864 8 0 6.331.864l-1.858.282-.842 1.68-1.337 1.32L2.6 6l-.306 1.854 1.337 1.32.842 1.68 1.858.282L8 12l1.669-.864 1.858-.282.842-1.68 1.337-1.32L13.4 6l.306-1.854-1.337-1.32-.842-1.68zm1.196 1.193.684 1.365 1.086 1.072L12.387 6l.248 1.506-1.086 1.072-.684 1.365-1.51.229L8 10.874l-1.355-.702-1.51-.229-.684-1.365-1.086-1.072L3.614 6l-.25-1.506 1.087-1.072.684-1.365 1.51-.229L8 1.126l1.356.702z'/>
                    <path d='M4 11.794V16l4-1 4 1v-4.206l-2.018.306L8 13.126 6.018 12.1z'/>
                  </svg>
                SVG"
              />
            @endif
            @if(has_module_access('members') && auth()->user()->getCachedPermissions()->contains('view_members'))
            <livewire:sidebar-menu-item
              :title="__('sidebar.member_management.title')"
              :icon="'<svg class=\'fill-current\' width=\'18\' height=\'18\' viewBox=\'0 0 18 18\' fill=\'none\' xmlns=\'http://www.w3.org/2000/svg\'><path d=\'M9 9C10.6575 9 12 7.6575 12 6C12 4.3425 10.6575 3 9 3C7.3425 3 6 4.3425 6 6C6 7.6575 7.3425 9 9 9ZM9 10.5C6.9975 10.5 3 11.505 3 13.5V15H15V13.5C15 11.505 11.0025 10.5 9 10.5Z\' fill=\'\'/></svg>'"
              link="members.index"
              @click="$dispatch('menu-item-clicked')"
            />
            @endif
            @if(has_module_access('staff') && auth()->user()->getCachedPermissions()->contains('view_staff'))
            <livewire:sidebar-menu-item
              :title="__('sidebar.staff_management.title')"
              :icon="<<<'SVG'
                  <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-person-bounding-box' viewBox='0 0 16 16'>
                      <path d='M1.5 1a.5.5 0 0 0-.5.5v3a.5.5 0 0 1-1 0v-3A1.5 1.5 0 0 1 1.5 0h3a.5.5 0 0 1 0 1zM11 .5a.5.5 0 0 1 .5-.5h3A1.5 1.5 0 0 1 16 1.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 1-.5-.5M.5 11a.5.5 0 0 1 .5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 1 0 1h-3A1.5 1.5 0 0 1 0 14.5v-3a.5.5 0 0 1 .5-.5m15 0a.5.5 0 0 1 .5.5v3a1.5 1.5 0 0 1-1.5 1.5h-3a.5.5 0 0 1 0-1h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 1 .5-.5'/>
                      <path d='M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm8-9a3 3 0 1 1-6 0 3 3 0 0 1 6 0'/>
                  </svg>
                SVG"
              link="staff.index"
              @click="$dispatch('menu-item-clicked')"
            />
            @endif
            @if((has_module_access('invoices') && auth()->user()->getCachedPermissions()->contains('view_invoices')) || 
                (has_module_access('payments') && auth()->user()->getCachedPermissions()->contains('view_payments')))
              @php
                $dropdownItems = [];
                if (has_module_access('invoices') && auth()->user()->getCachedPermissions()->contains('view_invoices')) {
                  $dropdownItems[] = ['id' => 'invoices', 'title' => __('sidebar.invoice_management.title'), 'link' => 'invoices.index'];
                }
                if (has_module_access('payments') && auth()->user()->getCachedPermissions()->contains('view_payments')) {
                  $dropdownItems[] = ['id' => 'payments', 'title' => __('sidebar.payment_management.title'), 'link' => 'payments.index'];
                }
              @endphp
              <livewire:sidebar-menu-item 
                :title="__('sidebar.finance.title')"
                :has-dropdown="true"
                :dropdown-items=$dropdownItems
                :icon="<<<'SVG'
                  <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-bank' viewBox='0 0 16 16'>
                      <path d='m8 0 6.61 3h.89a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.5.5H15v7a.5.5 0 0 1 .485.38l.5 2a.498.498 0 0 1-.485.62H.5a.498.498 0 0 1-.485-.62l.5-2A.5.5 0 0 1 1 13V6H.5a.5.5 0 0 1-.5-.5v-2A.5.5 0 0 1 .5 3h.89zM3.777 3h8.447L8 1zM2 6v7h1V6zm2 0v7h2.5V6zm3.5 0v7h1V6zm2 0v7H12V6zM13 6v7h1V6zm2-1V4H1v1zm-.39 9H1.39l-.25 1h13.72z'/>
                  </svg>
                SVG"
              />
            @endif
            @if((has_module_access('reports') && auth()->user()->getCachedPermissions()->contains('view_membership_reports')) || 
                (has_module_access('reports') && auth()->user()->getCachedPermissions()->contains('view_revenue_reports')))
              @php
                $dropdownItems = [];
                if (has_module_access('reports') && auth()->user()->getCachedPermissions()->contains('view_membership_reports')) {
                  $dropdownItems[] = ['id' => 'membership_reports', 'title' => __('sidebar.membership_reports.title'), 'link' => 'reports.membership'];
                }
                if (has_module_access('reports') && auth()->user()->getCachedPermissions()->contains('view_revenue_reports')) {
                  $dropdownItems[] = ['id' => 'revenue_reports', 'title' => __('sidebar.revenue_reports.title'), 'link' => 'reports.revenue'];
                }
              @endphp
              <livewire:sidebar-menu-item 
                :title="__('sidebar.reports.title')"
                :has-dropdown="true"
                :dropdown-items=$dropdownItems
                :icon="<<<'SVG'
                  <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-bar-chart-line' viewBox='0 0 16 16'>
                      <path d='M11 2a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v12h.5a.5.5 0 0 1 0 1H.5a.5.5 0 0 1 0-1H1v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3h1V7a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7h1zm1 12h2V2h-2zm-3 0V7H7v7zm-5 0v-3H2v3z'/>
                  </svg>
                SVG"
              />
            @endif
            @if(has_module_access('role_management') && auth()->user()->getCachedPermissions()->contains('manage_roles'))
              <livewire:sidebar-menu-item
              :title="__('sidebar.role_management.title')"
              :icon="<<<'SVG'
                  <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-file-earmark-lock' viewBox='0 0 16 16'>
                      <path d='M10 7v1.076c.54.166 1 .597 1 1.224v2.4c0 .816-.781 1.3-1.5 1.3h-3c-.719 0-1.5-.484-1.5-1.3V9.3c0-.627.46-1.058 1-1.224V7a2 2 0 1 1 4 0M7 7v1h2V7a1 1 0 0 0-2 0M6 9.3v2.4c0 .042.02.107.105.175A.64.64 0 0 0 6.5 12h3a.64.64 0 0 0 .395-.125c.085-.068.105-.133.105-.175V9.3c0-.042-.02-.107-.105-.175A.64.64 0 0 0 9.5 9h-3a.64.64 0 0 0-.395.125C6.02 9.193 6 9.258 6 9.3'/>
                      <path d='M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2M9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5z'/>
                  </svg>
                SVG"
              link="user-roles.index"
              @click="$dispatch('menu-item-clicked')"
          />
            @endif
            @if(has_module_access('messages') && auth()->user()->getCachedPermissions()->contains('view_messages'))
              <livewire:sidebar-menu-item
              :title="__('sidebar.message_management.title')"
              :icon="<<<'SVG'
                  <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-chat' viewBox='0 0 16 16'>
                      <path d='M2.678 11.894a1 1 0 0 1 .287.801 11 11 0 0 1-.398 2c1.395-.323 2.247-.697 2.634-.893a1 1 0 0 1 .71-.074A8 8 0 0 0 8 14c3.996 0 7-2.807 7-6s-3.004-6-7-6-7 2.808-7 6c0 1.468.617 2.83 1.678 3.894m-.493 3.905a22 22 0 0 1-.713.129c-.2.032-.352-.176-.273-.362a10 10 0 0 0 .244-.637l.003-.01c.248-.72.45-1.548.524-2.319C.743 11.37 0 9.76 0 8c0-3.866 3.582-7 8-7s8 3.134 8 7-3.582 7-8 7a9 9 0 0 1-2.347-.306c-.52.263-1.639.742-3.468 1.105'/>
                  </svg>
                SVG"
              link="messages.index"
              @click="$dispatch('menu-item-clicked')"
              />
            @endif
            @if((has_module_access('staff_attendance') && auth()->user()->getCachedPermissions()->contains('view_staff_attendance')) || 
                (has_module_access('member_attendance') && auth()->user()->getCachedPermissions()->contains('view_member_attendance')) || 
                (has_module_access('scan_attendance') && auth()->user()->getCachedPermissions()->contains('view_qr_codes')))
              @php
                $dropdownItems = [];
                if (has_module_access('member_attendance') && auth()->user()->getCachedPermissions()->contains('view_member_attendance')) {
                  $dropdownItems[] = ['id' => 'membersA', 'title' => __('sidebar.attendance_members.title'), 'link' => 'attendance.members.index'];
                }
                if (has_module_access('staff_attendance') && auth()->user()->getCachedPermissions()->contains('view_staff_attendance')) {
                  $dropdownItems[] = ['id' => 'staffA', 'title' => __('sidebar.attendance_staff.title'), 'link' => 'attendance.staff.index'];
                }
                if (has_module_access('scan_attendance') && auth()->user()->getCachedPermissions()->contains('view_qr_codes')) {
                  $dropdownItems[] = ['id' => 'qrCodes', 'title' => __('sidebar.attendance_qr_codes.title'), 'link' => 'attendance.qr-codes'];
                }
              @endphp
              <livewire:sidebar-menu-item 
                :title="__('sidebar.attendance.title')"
                :has-dropdown="true"
                :dropdown-items=$dropdownItems
                :icon="<<<'SVG'
                  <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-record-btn' viewBox='0 0 16 16'>
                    <path d='M8 11a3 3 0 1 0 0-6 3 3 0 0 0 0 6'/>
                    <path d='M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm15 0a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1z'/>
                  </svg>
                SVG"
              />
            @endif
            @if(has_module_access('settings') && (auth()->user()->getCachedPermissions()->contains('manage_settings')))
            @php
              $dropdownItems = [
                ['id' => 'app_settings', 'title' => __('sidebar.settings_app.title'), 'link' => 'settings.app_settings.index'],
                ['id' => 'currencies', 'title' => __('sidebar.settings_currencies.title'), 'link' => 'settings.currencies.index'],
                ['id' => 'taxes', 'title' => __('sidebar.settings_taxes.title'), 'link' => 'settings.taxes.index'],
                ['id' => 'products', 'title' => __('sidebar.settings_products.title'), 'link' => 'settings.products.index'],
                ['id' => 'staff_types', 'title' => __('sidebar.settings_staff_types.title'), 'link' => 'settings.staff_types.index'],
                ['id' => 'body_metrics', 'title' => __('sidebar.settings_body_metrics.title'), 'link' => 'settings.body_metrics.index'],
              ];
            @endphp
              <livewire:sidebar-menu-item 
                :title="__('sidebar.settings.title')"
                :has-dropdown="true"
                :dropdown-items=$dropdownItems
                :icon="<<<'SVG'
                  <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-gear' viewBox='0 0 16 16'>
                      <path d='M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492M5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0'/>
                      <path d='M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 0-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115z'/>
                  </svg>
                SVG"
              />
            @endif
          
        </ul>
      </div>
    </nav>
    <!-- Sidebar Menu -->
  </div>
</aside>
