@extends('layouts.admin')

@section('title', 'Gestion des Programmes')
@section('subtitle', 'Créez et gérez les programmes d\'intégration')

@section('content')
<div class="space-y-6">
    
    <!-- En-tête avec bouton de création -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Programmes</h1>
            <p class="text-gray-600">Liste de tous les programmes d'intégration</p>
        </div>
        <a href="{{ route('admin.programmes.create') }}" 
           class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent 
                  rounded-md font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 
                  focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-sm
                  transform hover:-translate-y-0.5">
            <i class="fas fa-plus-circle mr-2"></i> Nouveau Programme
        </a>
    </div>
    
    <!-- Tableau des programmes -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        @if($programmes->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Titre
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date & Heure
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Lieu
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Créé par
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($programmes as $programme)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-medium text-gray-900">{{ $programme->titre }}</div>
                                @if($programme->description)
                                <div class="text-sm text-gray-500 mt-1">
                                    {{ Str::limit($programme->description, 60) }}
                                </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 font-medium">
                                    {{ $programme->date_programme->format('d/m/Y') }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $programme->heure_debut }}
                                    @if($programme->heure_fin)
                                    - {{ $programme->heure_fin }}
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $programme->lieu ?? 'Non spécifié' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $programme->createur->name ?? 'Admin' }}</div>
                                <div class="text-xs text-gray-500">
                                    {{ $programme->created_at->format('d/m/Y') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <a href="{{ route('admin.programmes.edit', $programme) }}" 
                                   class="text-yellow-600 hover:text-yellow-900 px-2 py-1 rounded hover:bg-yellow-50"
                                   title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.programmes.destroy', $programme) }}" 
                                      method="POST" class="inline"
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce programme ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-900 px-2 py-1 rounded hover:bg-red-50"
                                            title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="px-4 py-3 border-t border-gray-200 bg-gray-50">
                {{ $programmes->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-100 mb-4">
                    <i class="fas fa-calendar-alt text-blue-600 text-2xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun programme créé</h3>
                <p class="text-gray-500 mb-6">Commencez par créer votre premier programme d'intégration</p>
                <a href="{{ route('admin.programmes.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md 
                          hover:bg-blue-700 transition-all duration-200">
                    <i class="fas fa-plus-circle mr-2"></i> Créer un programme
                </a>
            </div>
        @endif
    </div>
</div>

@if(session('success'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    alert('{{ session('success') }}');
});
</script>
@endif
@endsection