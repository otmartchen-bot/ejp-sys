@extends('layouts.admin')

@section('title', 'Nouveaux assignés')
@section('subtitle', 'Nouveaux suivis par ' . $user->name)

@section('content')
<div class="space-y-6">
    
    <!-- En-tête -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center">
            <a href="{{ route('admin.aides.show', $user) }}" 
               class="text-blue-600 hover:text-blue-900 mr-4">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Nouveaux assignés</h1>
                <p class="text-gray-600">Aide : {{ $user->name }}</p>
            </div>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('admin.nouveaux.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md 
                      hover:bg-green-700">
                <i class="fas fa-user-plus mr-2"></i> Assigner un nouveau
            </a>
        </div>
    </div>
    
    <!-- Tableau -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        @if($nouveaux->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nom</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Profession</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Présences</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($nouveaux as $nouveau)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-medium text-gray-900">{{ $nouveau->full_name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $nouveau->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $nouveau->profession }}</div>
                                <div class="text-sm text-gray-500">{{ $nouveau->fij }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-bold">{{ $nouveau->participations_count }}</span>
                                <span class="text-sm text-gray-500">présences</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <a href="{{ route('admin.nouveaux.show', $nouveau) }}" 
                                   class="text-blue-600 hover:text-blue-900 mr-3">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="#" class="text-red-600 hover:text-red-900"
                                   onclick="if(confirm('Retirer ce nouveau de cet aide ?')) {
                                       document.getElementById('remove-{{ $nouveau->id }}').submit();
                                   }">
                                    <i class="fas fa-user-minus"></i>
                                </a>
                                <form id="remove-{{ $nouveau->id }}" 
                                      action="{{ route('admin.aides.removeNouveau', [$user, $nouveau]) }}" 
                                      method="POST" class="hidden">
                                    @csrf @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="px-4 py-3 border-t border-gray-200">
                {{ $nouveaux->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-user-friends text-gray-300 text-5xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun nouveau assigné</h3>
                <p class="text-gray-500">Cet aide ne suit actuellement aucun nouveau</p>
            </div>
        @endif
    </div>
</div>
@endsection