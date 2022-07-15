v_dosen

select `akd`.`kdperson` AS `id_pegawai`,concat(`p`.`gelardepan`,'',`p`.`namalengkap`,' ',`p`.`gelarbelakang`) AS `nama`,`uk`.`unitkerja` AS `unitkerja`,`akd`.`nip` AS `nik`,`akd`.`nidn` AS `nidn`,`p`.`email` AS `email` from ((`simptt`.`ak_dosen` `akd` join `simptt`.`pt_person` `p` on((`akd`.`kdperson` = `p`.`kdperson`))) join `simptt`.`pt_unitkerja` `uk` on((`akd`.`kdunitkerja` = `uk`.`kdunitkerja`)))

v_mahasiswa

select `akm`.`kdmahasiswa` AS `kdmahasiswa`,`akm`.`nim` AS `STUDENTID`,`pp`.`namalengkap` AS `FULLNAME`,`pp`.`email` AS `email`,`uk`.`kdunitkerja` AS `DEPARTMENT_ID`,`uk`.`unitkerja` AS `NAME_OF_DEPARTMENT`,`uk`.`unitkerja` AS `NAME_OF_FACULTY` from ((`simptt`.`ak_mahasiswa` `akm` join `simptt`.`pt_unitkerja` `uk` on((`akm`.`kdunitkerja` = `uk`.`kdunitkerja`))) join `simptt`.`pt_person` `pp` on((`akm`.`kdperson` = `pp`.`kdperson`)))

mstr_department

select `p`.`kdunitkerja` AS `DEPARTMENT_ID`,`p`.`unitkerja` AS `NAME_OF_DEPARTMENT`,`p`.`leveling` AS `JENJANG`,`p`.`kdunitkerja` AS `FACULTY` from (`simptt`.`pt_unitkerja` `p` join `simptt`.`pt_unitkerja` `p2` on((`p2`.`kdunitkerja` = `p`.`kdunitkerjapj`))) where (`p2`.`sebutan` = 'dekan')

v_prestasi

Create view v_prestasi as SELECT        
tpp.id_penerbitan_pengajuan, tpp.nominal, tpp.status_pencairan, 
tpp.tanggal_pencairan, tpp.id_pengajuan, tpp.point,
vm.FULLNAME, vm.STUDENTID, vm.DEPARTMENT_ID, vm.NAME_OF_DEPARTMENT,
ppn.nama_periode, ppn.status, ppn.tanggal, YEAR(ppn.tanggal) as tahun,
p.Jenis_Pengajuan_Id, mjp.Jenis_Pengajuan
FROM            
tr_penerbitan_pengajuan AS tpp 
INNER JOIN v_mahasiswa AS vm ON vm.STUDENTID = tpp.STUDENTID 
INNER JOIN tr_periode_penerbitan AS ppn ON ppn.id_periode = tpp.id_periode
INNER JOIN tr_pengajuan AS p ON p.pengajuan_id = tpp.id_pengajuan
INNER JOIN mstr_jenis_pengajuan AS mjp ON mjp.Jenis_Pengajuan_Id = p.Jenis_Pengajuan_Id 