@extends('layouts.theme')

@section('content')
<main class="flex-1 overflow-x-auto overflow-y-hidden bg-slate-50/50 p-6">
    <div class="h-full grid grid-cols-1 md:grid-cols-3 gap-6 min-w-[1024px] max-w-[1600px] mx-auto">
        <div class="flex flex-col h-full bg-slate-100/70 rounded-xl border border-slate-200/60 shadow-inner">
            <div class="p-4 flex items-center justify-between border-b border-slate-200/60 bg-white/60 backdrop-blur-sm rounded-t-xl sticky top-0 z-10">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center size-6 rounded bg-red-100 text-red-600">
                        <span class="material-symbols-outlined text-[16px]">assignment_late</span>
                    </div>
                    <h3 class="font-bold text-slate-800 text-sm uppercase tracking-wide">New / Pending</h3>
                    <span class="bg-red-600 text-white text-xs font-bold px-2 py-0.5 rounded-full shadow-sm shadow-red-200">3</span>
                </div>
                <button class="text-slate-400 hover:text-slate-600 p-1 hover:bg-slate-200/50 rounded transition-colors"><span class="material-symbols-outlined text-[20px]">add</span></button>
            </div>
            <div class="p-3 flex-1 overflow-y-auto custom-scrollbar space-y-3">
                <div class="group bg-white p-4 rounded-lg shadow-sm border border-slate-200 hover:shadow-md hover:border-red-300 transition-all cursor-pointer relative overflow-hidden">
                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-red-500"></div>
                    <div class="flex justify-between items-start mb-2 pl-2">
                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-red-700 bg-red-50 px-2 py-0.5 rounded border border-red-100 uppercase tracking-wide">
                            <span class="material-symbols-outlined text-[12px]">warning</span> Critical
                        </span>
                        <span class="text-[11px] text-slate-400 font-mono font-medium">#9204</span>
                    </div>
                    <h4 class="font-bold text-slate-900 text-base mb-1 pl-2">Traffic Accident • Major Injury</h4>
                    <div class="flex items-start gap-2 text-slate-500 text-xs mb-3 pl-2">
                        <span class="material-symbols-outlined text-[16px] shrink-0 mt-0.5">location_on</span>
                        <span>124 Market St, Downtown<br /><span class="text-slate-400">Cross: 4th Ave &amp; Main</span></span>
                    </div>
                    <div class="flex items-center justify-between pt-3 border-t border-slate-50 mt-2 pl-2">
                        <div class="flex items-center gap-1.5 text-slate-500 text-xs font-medium">
                            <span class="material-symbols-outlined text-[16px]">schedule</span>
                            <span class="text-red-600 font-bold">2 min ago</span>
                        </div>
                        <div class="flex -space-x-2 opacity-50 group-hover:opacity-100 transition-opacity">
                            <div class="size-6 rounded-full bg-slate-100 border-2 border-white flex items-center justify-center text-[10px] text-slate-400">?</div>
                        </div>
                    </div>
                    <div class="absolute bottom-3 right-3 opacity-0 group-hover:opacity-100 transition-all translate-y-2 group-hover:translate-y-0">
                        <button class="bg-primary hover:bg-blue-600 text-white text-xs font-bold px-3 py-1.5 rounded shadow-lg shadow-primary/20">Assign</button>
                    </div>
                </div>
                <div class="group bg-white p-4 rounded-lg shadow-sm border border-slate-200 hover:shadow-md hover:border-orange-300 transition-all cursor-pointer relative overflow-hidden">
                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-orange-500"></div>
                    <div class="flex justify-between items-start mb-2 pl-2">
                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-orange-700 bg-orange-50 px-2 py-0.5 rounded border border-orange-100 uppercase tracking-wide">
                            High Priority
                        </span>
                        <span class="text-[11px] text-slate-400 font-mono font-medium">#9203</span>
                    </div>
                    <h4 class="font-bold text-slate-900 text-base mb-1 pl-2">Structural Fire</h4>
                    <div class="flex items-start gap-2 text-slate-500 text-xs mb-3 pl-2">
                        <span class="material-symbols-outlined text-[16px] shrink-0 mt-0.5">location_on</span>
                        <span>882 Pine Avenue, Westside</span>
                    </div>
                    <div class="flex items-center justify-between pt-3 border-t border-slate-50 mt-2 pl-2">
                        <div class="flex items-center gap-1.5 text-slate-500 text-xs font-medium">
                            <span class="material-symbols-outlined text-[16px]">schedule</span>
                            <span>8 min ago</span>
                        </div>
                    </div>
                </div>
                <div class="group bg-white p-4 rounded-lg shadow-sm border border-slate-200 hover:shadow-md hover:border-yellow-300 transition-all cursor-pointer relative overflow-hidden">
                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-yellow-400"></div>
                    <div class="flex justify-between items-start mb-2 pl-2">
                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-yellow-700 bg-yellow-50 px-2 py-0.5 rounded border border-yellow-100 uppercase tracking-wide">
                            Medium
                        </span>
                        <span class="text-[11px] text-slate-400 font-mono font-medium">#9201</span>
                    </div>
                    <h4 class="font-bold text-slate-900 text-base mb-1 pl-2">Suspicious Activity</h4>
                    <div class="flex items-start gap-2 text-slate-500 text-xs mb-3 pl-2">
                        <span class="material-symbols-outlined text-[16px] shrink-0 mt-0.5">location_on</span>
                        <span>Central Park, North Entrance</span>
                    </div>
                    <div class="flex items-center justify-between pt-3 border-t border-slate-50 mt-2 pl-2">
                        <div class="flex items-center gap-1.5 text-slate-500 text-xs font-medium">
                            <span class="material-symbols-outlined text-[16px]">schedule</span>
                            <span>15 min ago</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex flex-col h-full bg-slate-100/70 rounded-xl border border-slate-200/60 shadow-inner">
            <div class="p-4 flex items-center justify-between border-b border-slate-200/60 bg-white/60 backdrop-blur-sm rounded-t-xl sticky top-0 z-10">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center size-6 rounded bg-blue-100 text-blue-600">
                        <span class="material-symbols-outlined text-[16px]">timelapse</span>
                    </div>
                    <h3 class="font-bold text-slate-800 text-sm uppercase tracking-wide">In Progress</h3>
                    <span class="bg-slate-200 text-slate-600 text-xs font-bold px-2 py-0.5 rounded-full">5</span>
                </div>
                <button class="text-slate-400 hover:text-slate-600 p-1 hover:bg-slate-200/50 rounded transition-colors"><span class="material-symbols-outlined text-[20px]">sort</span></button>
            </div>
            <div class="p-3 flex-1 overflow-y-auto custom-scrollbar space-y-3">
                <div class="group bg-white p-4 rounded-lg shadow-sm border border-slate-200 hover:shadow-md transition-all cursor-pointer relative">
                    <div class="flex justify-between items-start mb-2">
                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-blue-700 bg-blue-50 px-2 py-0.5 rounded border border-blue-100 uppercase tracking-wide">
                            Medical
                        </span>
                        <span class="text-[11px] text-slate-400 font-mono font-medium">#9198</span>
                    </div>
                    <h4 class="font-bold text-slate-900 text-base mb-1">Cardiac Arrest</h4>
                    <div class="flex items-start gap-2 text-slate-500 text-xs mb-4">
                        <span class="material-symbols-outlined text-[16px] shrink-0 mt-0.5">location_on</span>
                        <span>Fitness Center, 4th St</span>
                    </div>
                    <div class="bg-slate-50 rounded p-2 border border-slate-100">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-[10px] uppercase font-bold text-slate-400">Assigned Units</span>
                            <span class="text-[10px] font-bold text-green-600 bg-green-50 px-1.5 py-0.5 rounded">On Scene</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="flex -space-x-2">
                                    <div class="size-7 rounded-full bg-white border-2 border-slate-100 flex items-center justify-center shadow-sm" title="Medic 202">
                                        <span class="material-symbols-outlined text-[14px] text-red-500">ambulance</span>
                                    </div>
                                    <div class="size-7 rounded-full bg-white border-2 border-slate-100 flex items-center justify-center shadow-sm" title="Police 104">
                                        <span class="material-symbols-outlined text-[14px] text-blue-500">local_police</span>
                                    </div>
                                </div>
                                <span class="text-xs text-slate-500">+1 enroute</span>
                            </div>
                            <span class="text-xs font-mono text-slate-400">04:32</span>
                        </div>
                    </div>
                </div>
                <div class="group bg-white p-4 rounded-lg shadow-sm border border-slate-200 hover:shadow-md transition-all cursor-pointer relative">
                    <div class="flex justify-between items-start mb-2">
                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-purple-700 bg-purple-50 px-2 py-0.5 rounded border border-purple-100 uppercase tracking-wide">
                            Police
                        </span>
                        <span class="text-[11px] text-slate-400 font-mono font-medium">#9185</span>
                    </div>
                    <h4 class="font-bold text-slate-900 text-base mb-1">Domestic Disturbance</h4>
                    <div class="flex items-start gap-2 text-slate-500 text-xs mb-4">
                        <span class="material-symbols-outlined text-[16px] shrink-0 mt-0.5">location_on</span>
                        <span>4500 block, Oak Street</span>
                    </div>
                    <div class="bg-slate-50 rounded p-2 border border-slate-100">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-[10px] uppercase font-bold text-slate-400">Assigned Units</span>
                            <span class="text-[10px] font-bold text-blue-600 bg-blue-50 px-1.5 py-0.5 rounded">Investigating</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="flex -space-x-2">
                                    <div class="size-7 rounded-full bg-white border-2 border-slate-100 flex items-center justify-center shadow-sm">
                                        <span class="text-[10px] font-bold text-slate-700">P4</span>
                                    </div>
                                    <div class="size-7 rounded-full bg-white border-2 border-slate-100 flex items-center justify-center shadow-sm">
                                        <span class="text-[10px] font-bold text-slate-700">P7</span>
                                    </div>
                                </div>
                            </div>
                            <span class="text-xs font-mono text-slate-400">24:00</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex flex-col h-full bg-slate-100/70 rounded-xl border border-slate-200/60 shadow-inner opacity-90">
            <div class="p-4 flex items-center justify-between border-b border-slate-200/60 bg-white/60 backdrop-blur-sm rounded-t-xl sticky top-0 z-10">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center size-6 rounded bg-emerald-100 text-emerald-600">
                        <span class="material-symbols-outlined text-[16px]">check_circle</span>
                    </div>
                    <h3 class="font-bold text-slate-800 text-sm uppercase tracking-wide">Completed</h3>
                    <span class="bg-slate-200 text-slate-600 text-xs font-bold px-2 py-0.5 rounded-full">12</span>
                </div>
                <button class="text-slate-400 hover:text-slate-600 p-1 hover:bg-slate-200/50 rounded transition-colors"><span class="material-symbols-outlined text-[20px]">history</span></button>
            </div>
            <div class="p-3 flex-1 overflow-y-auto custom-scrollbar space-y-3">
                <div class="group bg-white p-4 rounded-lg shadow-sm border border-slate-200 hover:shadow-md transition-all cursor-pointer relative grayscale hover:grayscale-0">
                    <div class="flex justify-between items-start mb-2">
                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-slate-600 bg-slate-100 px-2 py-0.5 rounded border border-slate-200 uppercase tracking-wide">
                            Other
                        </span>
                        <span class="text-[11px] text-slate-400 font-mono font-medium">#9150</span>
                    </div>
                    <h4 class="font-bold text-slate-700 text-base mb-1 line-through decoration-slate-300 decoration-2 group-hover:no-underline group-hover:text-slate-900">False Alarm (Fire)</h4>
                    <div class="flex items-start gap-2 text-slate-400 text-xs mb-3">
                        <span class="material-symbols-outlined text-[16px] shrink-0 mt-0.5">location_on</span>
                        <span>Riverside High School</span>
                    </div>
                    <div class="flex items-center justify-between pt-3 border-t border-slate-50 mt-2">
                        <div class="flex items-center gap-1.5 text-emerald-600 text-xs font-medium">
                            <span class="material-symbols-outlined text-[16px]">done_all</span>
                            <span>Resolved: 10:45 AM</span>
                        </div>
                    </div>
                </div>
                <div class="group bg-white p-4 rounded-lg shadow-sm border border-slate-200 hover:shadow-md transition-all cursor-pointer relative grayscale hover:grayscale-0">
                    <div class="flex justify-between items-start mb-2">
                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-slate-600 bg-slate-100 px-2 py-0.5 rounded border border-slate-200 uppercase tracking-wide">
                            Traffic
                        </span>
                        <span class="text-[11px] text-slate-400 font-mono font-medium">#9142</span>
                    </div>
                    <h4 class="font-bold text-slate-700 text-base mb-1 line-through decoration-slate-300 decoration-2 group-hover:no-underline group-hover:text-slate-900">Minor Collision</h4>
                    <div class="flex items-start gap-2 text-slate-400 text-xs mb-3">
                        <span class="material-symbols-outlined text-[16px] shrink-0 mt-0.5">location_on</span>
                        <span>Main St &amp; 1st Ave</span>
                    </div>
                    <div class="flex items-center justify-between pt-3 border-t border-slate-50 mt-2">
                        <div class="flex items-center gap-1.5 text-emerald-600 text-xs font-medium">
                            <span class="material-symbols-outlined text-[16px]">done_all</span>
                            <span>Resolved: 09:12 AM</span>
                        </div>
                    </div>
                </div>
                <div class="group bg-white p-4 rounded-lg shadow-sm border border-slate-200 hover:shadow-md transition-all cursor-pointer relative grayscale hover:grayscale-0">
                    <div class="flex justify-between items-start mb-2">
                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-slate-600 bg-slate-100 px-2 py-0.5 rounded border border-slate-200 uppercase tracking-wide">
                            Public Safety
                        </span>
                        <span class="text-[11px] text-slate-400 font-mono font-medium">#9138</span>
                    </div>
                    <h4 class="font-bold text-slate-700 text-base mb-1 line-through decoration-slate-300 decoration-2 group-hover:no-underline group-hover:text-slate-900">Noise Complaint</h4>
                    <div class="flex items-start gap-2 text-slate-400 text-xs mb-3">
                        <span class="material-symbols-outlined text-[16px] shrink-0 mt-0.5">location_on</span>
                        <span>220 Sunset Blvd</span>
                    </div>
                    <div class="flex items-center justify-between pt-3 border-t border-slate-50 mt-2">
                        <div class="flex items-center gap-1.5 text-emerald-600 text-xs font-medium">
                            <span class="material-symbols-outlined text-[16px]">done_all</span>
                            <span>Resolved: 08:30 AM</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection