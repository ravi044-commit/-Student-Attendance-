<form method="POST" action="{{ $action }}" class="max-w-2xl space-y-5 rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    @if ($errors->any())
        <div class="rounded-lg border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800">
            <p class="font-semibold">Please correct the following:</p>
            <ul class="mt-2 list-inside list-disc">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <div>
        <label for="name" class="mb-1 block text-sm font-semibold text-slate-700">Full name</label>
        <input id="name" name="name" value="{{ old('name', $student?->name) }}" required maxlength="255"
               class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
    </div>
    <div>
        <label for="roll_number" class="mb-1 block text-sm font-semibold text-slate-700">Roll number</label>
        <input id="roll_number" name="roll_number" value="{{ old('roll_number', $student?->roll_number) }}" required maxlength="50"
               class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
    </div>
    <div>
        <label for="email" class="mb-1 block text-sm font-semibold text-slate-700">Email address</label>
        <input id="email" name="email" type="email" value="{{ old('email', $student?->email) }}" required maxlength="255"
               class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
    </div>
    <div>
        <label for="course_id" class="mb-1 block text-sm font-semibold text-slate-700">Course</label>
        <select id="course_id" name="course_id" required class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <option value="">Select a course</option>
            @foreach ($courses as $course)
                <option value="{{ $course->id }}" @selected((string) old('course_id', $student?->course_id) === (string) $course->id)>
                    {{ $course->name }} ({{ $course->code }})
                </option>
            @endforeach
        </select>
    </div>
    <div class="flex gap-3 pt-2">
        <button class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700">{{ $button }}</button>
        <a href="{{ route('students.index') }}" class="rounded-lg border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Cancel</a>
    </div>
</form>
