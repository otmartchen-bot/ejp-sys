<!-- Date du programme -->
<div>
    <label for="date_programme" class="block text-sm font-medium text-gray-700">
        Date du programme *
    </label>
    <input type="date" name="date_programme" id="date_programme" required
           value="{{ old('date_programme', $programme->date_only) }}"
           class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm 
                  sm:text-sm border-gray-300 rounded-md transition-all duration-200
                  hover:border-blue-300">
</div>

<!-- Heure de début -->
<div>
    <label for="heure_debut" class="block text-sm font-medium text-gray-700">
        Heure de début *
    </label>
    <input type="time" name="heure_debut" id="heure_debut" required
           value="{{ old('heure_debut', $programme->heure_debut ?? $programme->heure_only) }}"
           class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm 
                  sm:text-sm border-gray-300 rounded-md transition-all duration-200
                  hover:border-blue-300">
</div>

<!-- Heure de fin -->
<div>
    <label for="heure_fin" class="block text-sm font-medium text-gray-700">
        Heure de fin (optionnel)
    </label>
    <input type="time" name="heure_fin" id="heure_fin"
           value="{{ old('heure_fin', $programme->heure_fin) }}"
           class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm 
                  sm:text-sm border-gray-300 rounded-md transition-all duration-200
                  hover:border-blue-300">
</div>

<!-- Lieu -->
<div>
    <label for="lieu" class="block text-sm font-medium text-gray-700">
        Lieu
    </label>
    <input type="text" name="lieu" id="lieu"
           value="{{ old('lieu', $programme->lieu) }}"
           class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm 
                  sm:text-sm border-gray-300 rounded-md transition-all duration-200
                  hover:border-blue-300"
           placeholder="Ex: Temple principal, Salle de réunion...">
</div>