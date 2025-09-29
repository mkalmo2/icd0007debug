Silumise ja testimise ülesanded

Kopeerige praktikumi projekti (icd0007debug) juurkausta sisu oma
projekti (icd0007) kausta "ex5". NB! .git ja .idea kataloogi ärge
kopeerige.

1)  Failis radios.php on rakendus, mis kuvab viis raadio nuppu ja
    määrab, et üks neist on valitud. Vaikimisi on valitud nupp
    väärtuseta 3. See osa töötab.

    GET parameetriga peaks saama ette anda, milline nupp on valitud.

    Aadressi radios.php?grade=4 puhul peaks olema valitud nupp 4 aga see
    ei tööta.

    Uurige välja, milles on probleem.

    a)  Kasutades testimiseks brauserit.

    b)  Kasutades testimiseks icd0007tests repost testi failist ex5a.php
        nimega 'Checks correct radio'.

2)  Selle ülesande mõte on demonstreerida faili kaasamist ja info
    saatmist, kui kaasatavasse faili. Lisaks on eesmärk õppida sellisest
    koodist vigade leidmist.

    Kataloogis "include" on rakendus, mis kuvab viis raadio nuppu ja
    määrab, et üks neist on valitud. Vaikimisi on valitud nupp
    väärtuseta 3. See osa töötab.

    GET parameetriga peaks saama ette anda, milline hinne on valitud.

    Aadressi index.php?grade=4 puhul peaks olema valitud valik 4 aga see
    ei tööta.

    Uurige välja, milles on probleem ja parandage kood ära. Muuta võite
    vaid faili index.php.

3)  Kataloogis "confirm" on rakendus, mis teeb järgnevat.

    Kui mingi väärtus sisestada ja nupule vajutada, siis peaks
    kasutajalt kinnitust küsitama. Kui kasutaja kinnituse annab, siis
    peaks sisestatud info järgmisel lehel näha olema.

    Rakendus peaaegu töötab aga probleem on siis, kui andmed sisaldavad
    tühikut, ühekordseid jutumärke või mõnda muud probleemset sümbolit.

    Kasutage vea otsimise võtteid, et selgeks teha, milles on probleem.

    Kontrollige, et kood läbib testid nimedega 'Confirmation works with
    simple text' ja 'Confirmation works with different symbols'.

4)  Kataloogis "redirect" on rakendus, mis delegeerib vormi
    salvestamise, eraldi failile, mis pärast esimesele lehele tagasi
    suunab saates kaasa teate.

    Kontrollige meetodiga error_log() kas vormilt tulnud andmed jõuavad
    failini saver.php.

    Tehke nii, et rakendus võimaldaks teate sees ka reavahetusi.

5)  Kataloogis "calc" on rakendus, mis võimaldab teha arvude liitmise ja
    lahutamise tehteid.

    Rakenduse liides on väga ebamugav ja see on meelega nii tehtud.

    Rakenduse testimiseks on test nimega 'Calculates arithmetic
    expressions'.

    See test praegu läbi ei lähe. Proovige selle testi abil selgeks
    teha, kus on viga.

    Kasuks võivad olla testides kasutatavad järgmised funktsioonid.

    getFieldValue('välja nimi') - Tagastab vastava nimega vormi väljal
    oleva väärtuse.

    setLogRequests(true) - Väljastab konsooli info tehtud päringute
    kohta. Sama info leiate ka serveri konsoolist.

    setLogPostParameters(true) - Väljastab konsooli info tehtud POST
    päringute parameetrite kohta.

6)  Failis relative-path/reader1.php on kood, mis üritab lugeda faili
    sisu relatiivselt aadressilt.

    Proovige seda käivitada kataloogist relative-path ja repo
    juurkataloogist.

    Failis reader2.php on kood, mis ilmnendu probleemi paraneb.

    Failis reader-debug.php on näide kuidas kontrollida, mis kataloogist
    rakendus relatiivse aadressiga faile otsib.

7)  Fail path-in-web/index.php kaasab (include) faili pages/page.html.
    Määrake fails img tag-i src attribuut nii, et index.php näitaks
    brauseris pilti.

8)  Selle ülesande mõte on harjutada andmete vahetamist programmi
    erinevate osade vahel. Seekord on enamus programmist küll ühes
    failis aga infovahetus on sarnane mitmest failist koosneva rakenduse
    omale. Programmi olek iseenesest ei säili ja järgmisel käivitamisele
    ei teata eelmisest seisust midagi. Kui soovime programmi olekut
    erinevate pöördumiste jooksul säilitada, peame igal pöördumisel
    oleku edasi saatma.

Failis state/index.php on osa rakendusest, mille peate lõpuni kirjutama.

Rakendus näitab kahte nimekirja (select) numbritest ja nuppe, mis
võimaldavad numbreid ühest nimekirjast teise liigutada.

Kui valida number esimesest nimekirjast ja vajutada nupule "\>\>", siis
eemaldatakse number esimesest nimekirjast ja lisatakse teise.

Kui valida number teisest nimekirjast ja vajutada nupule "\<\<", siis
eemaldatakse number teisest nimekirjast ja lisatakse esimesse.

Esialgne seis on muutujates \$list1 ja \$list2.

Kirjutage puuduvad osad, et rakendus tööle hakkaks.

Infoks: Te peate programmi seisu iga päringuga edasi kandma. Infot saate
hoida vormi "hidden" väljadel.

    serialize() - transformeerib listi teksti kujule.
    unserialize() - transformeerib teksti tagasi listiks.

    Failis functions.php on abifunktsioon removeElementByValue($value, $array),
    mis eemaldab listist ette antud elemendi ja tagastab listi,
    millest on element eemaldatud.

Rakenduse testimiseks on testid failis ex5b.php nimedega 'Default page
has correct fields and values', 'Can move items from list1 to list2' ja
'Can move items from list2 to list1'.

9)  Commit-ige muudatused ja push-ige need Github'i.

    Lisage commit-ile tag ex5.

    Veenduge tulemuste lehelt, et kõik õnnestus.

Selgitused ja lahendused: https://youtu.be/JiB0LeEnbo4
