<nav class="border-b border-slate-200 bg-white">
    <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
        <a href="{{ route('dashboard') }}" class="text-lg font-bold tracking-tight text-indigo-700">
            Student Attendance
        </a>

        <div class="flex flex-wrap items-center gap-1 text-sm font-medium">
            @php
                $links = [
                    ['route' => 'dashboard', 'label' => 'Dashboard', 'active' => 'dashboard'],
                    ['route' => 'students.index', 'label' => 'Students', 'active' => 'students.*'],
                    ['route' => 'courses.index', 'label' => 'Courses', 'active' => 'courses.*'],
                    ['route' => 'attendance.create', 'label' => 'Mark attendance', 'active' => 'attendance.create'],
                    ['route' => 'attendance.index', 'label' => 'Records', 'active' => 'attendance.index'],
                    ['route' => 'reports.index', 'label' => 'Reports', 'active' => 'reports.*'],
                ];
            @endphp
            @foreach ($links as $link)
                <a href="{{ route($link['route']) }}"
                   @class([
                       'rounded-md px-3 py-2 transition',
                       'bg-indigo-50 text-indigo-700' => request()->routeIs($link['active']),
                       'text-slate-600 hover:bg-slate-100 hover:text-slate-900' => ! request()->routeIs($link['active']),
                   ])>
                    {{ $link['label'] }}
                </a>
            @endforeach
        </div>

        <div class="flex items-center gap-3">
            <span class="hidden text-sm text-slate-600 sm:inline">{{ Auth::user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="rounded-md border border-slate-300 px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                    Log out
                </button>
            </form>
        </div>
    </div>
</nav>
