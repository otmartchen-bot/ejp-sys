<!-- resources/views/aide/participations/programmes.blade.php -->
@extends('layouts.app') <!-- OU ton layout aide -->

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <a href="{{ route('aide.nouveaux.index') }}" class="text-blue-600 hover:text-blue-800 inline-flex items-center mb-4">
            <i class="fas fa-arrow-left mr-2"></i> Retour aux nouveaux
        </a>
        <h1 class="text-3xl font-bold text-gray-800">
            <i class="fas fa-calendar-alt mr-2"></i>Programmes de la semaine
        </h1>
        <p class="text-gray-600">Sélectionnez un programme pour marquer la présence</p>
    </div>

    @if($programmes->isEmpty())
        <div class="text-center py-12 bg-white rounded-xl shadow">
            <i class="fas fa-calendar-times text-gray-300 text-5xl mb-4"></i>
            <h3 class="text-xl font-bold text-gray-700 mb-2">Aucun programme cette semaine</h3>
            <p class="text-gray-500">Les programmes à venir apparaîtront ici</p>
        </div>
    @else
        <div class="grid gap-6">
            @foreach($programmes as $programme)
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-gray-800">{{ $programme->titre }}</h3>
                            <div class="mt-2 text-gray-600">
                                <i class="fas fa-calendar-day mr-2"></i>
                                {{ $programme->date_programme->format('d/m/Y H:i') }}
                            </div>
                            @if($programme->description)
                                <p class="mt-3 text-gray-700">{{ $programme->description }}</p>
                            @endif
                            @if($programme->lieu)
                                <p class="mt-2 text-gray-600">
                                    <i class="fas fa-map-marker-alt mr-2"></i>{{ $programme->lieu }}
                                </p>
                            @endif
                        </div>
                        
                        <div class="flex flex-col sm:flex-row gap-3">
                            <!-- Sélection d'un nouveau -->
                            <select id="nouveau-select-{{ $programme->id }}" 
                                    class="border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Sélectionner un nouveau</option>
                                @foreach($nouveaux as $nouveau)
                                    <option value="{{ $nouveau->id }}">
                                        {{ $nouveau->prenom }} {{ $nouveau->nom }}
                                    </option>
                                @endforeach
                            </select>
                            
                            <!-- Bouton pour ouvrir le modal -->
                            <button onclick="ouvrirModalPresence({{ $programme->id }})"
                                    class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                <i class="fas fa-check-circle mr-2"></i> Marquer présence
                            </button>
                        </div>
                    </div>
                    
                    <!-- Modal pour ce programme -->
                    <div id="modal-{{ $programme->id }}" class="hidden mt-4 p-4 bg-gray-50 rounded-lg">
                        <h4 class="font-bold text-gray-800 mb-3">Marquer présence/absence</h4>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                                <div class="flex space-x-4">
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="presence-{{ $programme->id }}" 
                                               value="present" class="h-5 w-5 text-green-600" checked>
                                        <span class="ml-2 text-gray-700">Présent</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="presence-{{ $programme->id }}" 
                                               value="absent" class="h-5 w-5 text-red-600">
                                        <span class="ml-2 text-gray-700">Absent</span>
                                    </label>
                                </div>
                            </div>
                            
                            <div id="motif-container-{{ $programme->id }}" class="hidden">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Motif d'absence</label>
                                <textarea id="motif-{{ $programme->id }}" rows="3" 
                                          class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                          placeholder="Raison de l'absence..."></textarea>
                            </div>
                            
                            <div class="flex justify-end space-x-3">
                                <button onclick="fermerModal({{ $programme->id }})"
                                        class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                                    Annuler
                                </button>
                                <button onclick="enregistrerParticipation({{ $programme->id }})"
                                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                                    <i class="fas fa-save mr-2"></i> Enregistrer
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<script>
    // Gérer l'affichage du champ motif d'absence
    document.addEventListener('DOMContentLoaded', function() {
        @foreach($programmes as $programme)
            const radios{{ $programme->id }} = document.querySelectorAll('input[name="presence-{{ $programme->id }}"]');
            radios{{ $programme->id }}.forEach(radio => {
                radio.addEventListener('change', function() {
                    const motifContainer = document.getElementById('motif-container-{{ $programme->id }}');
                    motifContainer.classList.toggle('hidden', this.value !== 'absent');
                });
            });
        @endforeach
    });
    
    function ouvrirModalPresence(programmeId) {
        const select = document.getElementById('nouveau-select-' + programmeId);
        const nouveauId = select.value;
        
        if (!nouveauId) {
            alert('Veuillez sélectionner un nouveau');
            return;
        }
        
        // Afficher le modal
        document.getElementById('modal-' + programmeId).classList.remove('hidden');
    }
    
    function fermerModal(programmeId) {
        document.getElementById('modal-' + programmeId).classList.add('hidden');
    }
    
    function enregistrerParticipation(programmeId) {
        const select = document.getElementById('nouveau-select-' + programmeId);
        const nouveauId = select.value;
        const presence = document.querySelector('input[name="presence-' + programmeId + '"]:checked').value;
        const motif = document.getElementById('motif-' + programmeId)?.value || '';
        
        // Envoyer les données au serveur
        fetch("{{ route('aide.participations.enregistrer') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                nouveau_id: nouveauId,
                programme_id: programmeId,
                present: presence === 'present',
                motif_absence: presence === 'absent' ? motif : null
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Participation enregistrée avec succès !');
                fermerModal(programmeId);
                select.value = ''; // Réinitialiser le select
            } else {
                alert('Erreur: ' + (data.message || 'Une erreur est survenue'));
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors de l\'enregistrement. Vérifiez votre connexion.');
        });
    }
</script>
@endsection