@extends('layouts.admin')

@section('title', 'Gestion des Aides')
@section('subtitle', 'Liste de tous les aides à l\'intégration')

@section('content')
<div class="space-y-6">
    
    <!-- En-tête avec bouton de création -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Aides à l'Intégration</h1>
            <p class="text-gray-600">Gérez les aides et leurs assignations</p>
        </div>
        <a href="{{ route('admin.aides.create') }}" 
           class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent 
                  rounded-md font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 
                  focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-sm">
            <i class="fas fa-user-plus mr-2"></i> Nouvel Aide
        </a>
    </div>
    
    <!-- Tableau des aides -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        @if($aides->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aide
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Contact
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nouveaux assignés
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date d'inscription
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($aides as $aide)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user text-blue-600"></i>
                                    </div>
                                    <div class="ml-4">
                                        <div class="font-medium text-gray-900">{{ $aide->name }}</div>
                                        <div class="text-sm text-gray-500">
                                            ID: #{{ str_pad($aide->id, 4, '0', STR_PAD_LEFT) }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $aide->email }}</div>
                                <div class="text-sm text-gray-500">
                                    {{ $aide->telephone ?? 'Non renseigné' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            bg-green-100 text-green-800">
                                    {{ $aide->nouveaux_count }} nouveaux
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $aide->created_at->format('d/m/Y') }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    Inscrit il y a {{ $aide->created_at->diffForHumans() }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <a href="{{ route('admin.aides.show', $aide) }}" 
                                   class="text-blue-600 hover:text-blue-900 px-2 py-1 rounded hover:bg-blue-50"
                                   title="Voir détails">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.aides.edit', $aide) }}" 
                                   class="text-yellow-600 hover:text-yellow-900 px-2 py-1 rounded hover:bg-yellow-50"
                                   title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('admin.aides.nouveaux', $aide) }}" 
                                   class="text-green-600 hover:text-green-900 px-2 py-1 rounded hover:bg-green-50"
                                   title="Voir nouveaux assignés">
                                    <i class="fas fa-users"></i>
                                </a>
                                <form action="{{ route('admin.aides.destroy', $aide) }}" 
                                      method="POST" class="inline"
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet aide ?')">
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
                {{ $aides->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-100 mb-4">
                    <i class="fas fa-user-friends text-blue-600 text-2xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun aide créé</h3>
                <p class="text-gray-500 mb-6">Commencez par créer votre premier aide à l'intégration</p>
                <a href="{{ route('admin.aides.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md 
                          hover:bg-blue-700 transition-all duration-200">
                    <i class="fas fa-user-plus mr-2"></i> Créer un aide
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