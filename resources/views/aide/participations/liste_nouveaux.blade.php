<div class="space-y-4">
    @foreach($nouveaux as $nouveau)
        <div class="border rounded-lg p-4">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <!-- Informations du nouveau -->
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                        <span class="font-bold text-blue-700">
                            {{ substr($nouveau->prenom, 0, 1) }}{{ substr($nouveau->nom, 0, 1) }}
                        </span>
                    </div>
                    <div>
                        <div class="font-bold text-gray-800">{{ $nouveau->prenom }} {{ $nouveau->nom }}</div>
                        <div class="text-sm text-gray-600">{{ $nouveau->profession }}</div>
                    </div>
                </div>
                
                <!-- Statut actuel -->
                <div class="text-center">
                    <span id="statut-{{ $nouveau->id }}" class="px-3 py-1 rounded-full text-sm font-medium 
                        @if($nouveau->participation && $nouveau->participation->present) bg-green-100 text-green-800
                        @elseif($nouveau->participation && !$nouveau->participation->present) bg-red-100 text-red-800
                        @else bg-gray-100 text-gray-800 @endif">
                        @if($nouveau->participation)
                            {{ $nouveau->participation->present ? 'Présent' : 'Absent' }}
                        @else
                            Non marqué
                        @endif
                    </span>
                </div>
                
                <!-- Boutons présence/absence -->
                <div class="flex space-x-2">
                    <button id="btn-present-{{ $nouveau->id }}"
                            onclick="marquerPresence({{ $nouveau->id }}, {{ $programme->id }}, true)"
                            class="px-4 py-2 rounded-lg 
                                @if($nouveau->participation && $nouveau->participation->present) bg-green-600 text-white
                                @else bg-green-100 text-green-700 hover:bg-green-200 @endif">
                        <i class="fas fa-check mr-1"></i> Présent
                    </button>
                    
                    <button id="btn-absent-{{ $nouveau->id }}"
                            onclick="toggleMotif({{ $nouveau->id }}, true); marquerPresence({{ $nouveau->id }}, {{ $programme->id }}, false)"
                            class="px-4 py-2 rounded-lg 
                                @if($nouveau->participation && !$nouveau->participation->present) bg-red-600 text-white
                                @else bg-red-100 text-red-700 hover:bg-red-200 @endif">
                        <i class="fas fa-times mr-1"></i> Absent
                    </button>
                </div>
            </div>
            
            <!-- Champ motif d'absence (caché par défaut) -->
            <div id="motif-container-{{ $nouveau->id }}" class="mt-3 hidden">
                <label class="block text-sm font-medium text-gray-700 mb-1">Motif d'absence</label>
                <textarea id="motif-{{ $nouveau->id }}" 
                          rows="2"
                          class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                          placeholder="Raison de l'absence...">{{ $nouveau->participation->motif_absence ?? '' }}</textarea>
                <div class="flex justify-end mt-2">
                    <button onclick="marquerPresence({{ $nouveau->id }}, {{ $programme->id }}, false)"
                            class="px-4 py-1 bg-red-600 text-white rounded hover:bg-red-700">
                        Enregistrer avec motif
                    </button>
                </div>
            </div>
            
            <!-- Affichage du motif existant -->
            @if($nouveau->participation && !$nouveau->participation->present && $nouveau->participation->motif_absence)
                <div class="mt-2 p-2 bg-red-50 rounded border border-red-100">
                    <div class="text-sm text-red-800">
                        <i class="fas fa-comment mr-1"></i>
                        {{ $nouveau->participation->motif_absence }}
                    </div>
                </div>
            @endif
        </div>
    @endforeach
    
    <!-- Bouton tout marquer présent -->
    <div class="mt-6 pt-4 border-t">
        <button onclick="marquerTousPresents({{ $programme->id }})"
                class="w-full py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium">
            <i class="fas fa-check-double mr-2"></i> Marquer tous présents
        </button>
    </div>
</div>

<script>
    function marquerTousPresents(programmeId) {
        if (confirm('Marquer tous les nouveaux comme présents ?')) {
            // Logique pour marquer tous présents
            alert('Tous marqués présents !');
        }
    }
</script>