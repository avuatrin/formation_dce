<h2>Page du membre : <?= $member->pseudo(); ?></h2><br><br>

<table>
    <tr>
        <th>Email</th><td><?= $member->email(); ?></td>
    </tr>
    <tr>
        <th>Bio</th><td><?= $member->philosophy(); ?></td>
    </tr>
    <tr>
        <th>Comments</th><td><?= $nbMessages; ?></td>
    </tr>
    <tr>
        <th>News</th><td><?= $nbNews; ?></td>
    </tr>
</table>

