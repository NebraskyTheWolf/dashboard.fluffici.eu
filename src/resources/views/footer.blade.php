@guest
    <div class="m-4 text-center text-muted">
        <p class="text-white">Vytvořeno s 💚🦊 Vakea pro Fluffici</p>

        <small style="color: white;" id="version"> Verze : <i class="loading cog icon"></i></small>
        <br>
        <small style="color: white;" id="rev"> Rev : <i class="loading cog icon"></i></small>
        <br>
        <small onclick="window.BeamClient.start().then(() => console.log('Registered with beams!'))"> Klikněte zde pro povolení push notifikací </small>
    </div>
@else
@endguest
