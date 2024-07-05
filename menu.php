<!-- Menu latéral -->
<div class="sidebar" id="sidebar">
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link active" href="dashboard.php">Tableau de Bord</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="voter.php">Voter</a>
        </li>
        <?php if ($role == 'admin') : ?>
            <li class="nav-item">
                <a class="nav-link" href="ajouter_candidat.php">Ajouter un Candidat</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="gerer_candidats.php">Gérer les Candidats</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="votes_attente.php">Gérer votes</a>
            </li>
        <?php endif; ?>
        <li class="nav-item">
            <a class="nav-link" href="logout.php">Déconnexion</a>
        </li>
    </ul>
</div>

<!-- Chargement de Bootstrap JS et jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- Script JavaScript pour gérer le menu latéral -->
<script>
    $(document).ready(function() {
        // Toggle pour ouvrir/fermer le menu latéral
        $('#sidebarCollapse').on('click', function() {
            $('#sidebar').toggleClass('active');
            $('#main-content').toggleClass('active');
        });
    });
</script>
</body>

</html>