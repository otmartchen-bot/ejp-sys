@extends('layouts.admin')

@section('title', 'Gestion des Nouveaux')
@section('subtitle', 'Liste globale de tous les nouveaux inscrits')

@section('content')
<div class="space-y-6">
    
    <!-- En-tête avec bouton de création -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Tous les Nouveaux</h1>
            <p class="text-gray-600">Gestion globale des nouveaux inscrits</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.nouveaux.create') }}" 
               class="inline-flex items-center justify-center px-4 py-2 bg-green-600 border border-transparent 
                      rounded-md font-semibold text-white hover:bg-green-700 focus:outline-none focus:ring-2 
                      focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 shadow-sm
                      transform hover:-translate-y-0.5">
                <i class="fas fa-user-plus mr-2"></i> Ajouter un nouveau
            </a>
        </div>
    </div>
    
    <!-- Tableau des nouveaux -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        @if($nouveaux->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nouveau
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Contact
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Profession & FIJ
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Assigné à
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date d'enregistrement
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($nouveaux as $nouveau)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                        <span class="text-blue-600 font-bold">
                                            {{ strtoupper(substr($nouveau->prenom, 0, 1)) }}{{ strtoupper(substr($nouveau->nom, 0, 1)) }}
                                        </span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="font-medium text-gray-900">
                                            {{ $nouveau->prenom }} {{ $nouveau->nom }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            ID: #{{ str_pad($nouveau->id, 4, '0', STR_PAD_LEFT) }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $nouveau->email }}</div>
                                <div class="text-sm text-gray-500">
                                    {{ $nouveau->telephone ?? 'Non renseigné' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $nouveau->profession ?? 'Non spécifié' }}</div>
                                <div class="text-sm text-gray-500">
                                    FIJ: {{ $nouveau->fij ?? 'Non renseigné' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($nouveau->aide)
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8 bg-green-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-user text-green-600 text-xs"></i>
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $nouveau->aide->name }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                Aide à l'intégration
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-exclamation-circle mr-1"></i> Non assigné
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ \Carbon\Carbon::parse($nouveau->date_enregistrement)->format('d/m/Y') }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    Créé le {{ $nouveau->created_at->format('d/m/Y') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <a href="{{ route('admin.nouveaux.show', $nouveau) }}" 
                                   class="text-blue-600 hover:text-blue-900 px-2 py-1 rounded hover:bg-blue-50"
                                   title="Voir détails">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="#" 
                                   class="text-green-600 hover:text-green-900 px-2 py-1 rounded hover:bg-green-50"
                                   title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" 
                                        class="text-red-600 hover:text-red-900 px-2 py-1 rounded hover:bg-red-50"
                                        title="Supprimer"
                                        onclick="if(confirm('Supprimer {{ $nouveau->prenom }} {{ $nouveau->nom }} ?')) {
                                            document.getElementById('delete-{{ $nouveau->id }}').submit();
                                        }">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <form id="delete-{{ $nouveau->id }}" 
                                      action="{{ route('admin.nouveaux.destroy', $nouveau) }}" 
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
            <div class="px-4 py-3 border-t border-gray-200 bg-gray-50">
                {{ $nouveaux->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-100 mb-4">
                    <i class="fas fa-user-friends text-blue-600 text-2xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun nouveau inscrit</h3>
                <p class="text-gray-500 mb-6">Commencez par ajouter votre premier nouveau</p>
                <a href="{{ route('admin.nouveaux.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md 
                          hover:bg-green-700 transition-all duration-200">
                    <i class="fas fa-user-plus mr-2"></i> Ajouter un nouveau
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