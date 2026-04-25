<div class="insignia-card {{ $insignia->desbloqueada ? 'desbloqueada' : 'bloqueada' }} rareza-{{ $insignia->rarity }}"
     data-nombre="{{ $insignia->name }}"
     data-descripcion="{{ $insignia->desbloqueada ? $insignia->description : $insignia->texto_condicion }}"
     data-rareza="{{ match($insignia->rarity) {
         'comun'      => '⚪ Común',
         'rara'       => '🔵 Rara',
         'epica'      => '🟣 Épica',
         'legendaria' => '🟡 Legendaria',
     } }}">

    <div class="insignia-icono">{{ $insignia->icon }}</div>
    <span class="insignia-nombre">{{ $insignia->name }}</span>

    @if(!$insignia->desbloqueada)
        <span class="insignia-candado">🔒</span>
    @endif

</div>