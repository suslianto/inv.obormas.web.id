<?php
include "configuration/config_connect.php";
include "configuration/config_chmod.php";
$nouser= $_SESSION['nouser'];
$user= "SELECT * FROM user WHERE no='$nouser' ";
$query = mysqli_query($conn, $user);
$row  = mysqli_fetch_assoc($query);
$nama = $row['nama'];
$jabatan = $row['jabatan'];
$avatar = $row['avatar'];
?>
 <aside class="main-sidebar">

                <section class="sidebar">
                    <!-- Sidebar user panel -->
                    <div class="user-panel">
                        <div class="pull-left image">
                            <img src="<?php  echo $avatar; ?>" class="img-circle" alt="User Image">
                        </div>
                        <div class="pull-left info">
                            <p><?php  echo $nama; ?></p>
                            <a href="#"><i class="fa fa-circle text-online"></i> Online</a>
                            
                        </div>
                    </div>
<br>
                             <ul class="sidebar-menu">
                       <!-- <li class="header">MENU UTAMA</li> -->
                        <li class="treeview">
                            <a href="index"> <i class="fa fa-dashboard"></i> <span>Dashboard</span> </a>

                        </li>



<?php

if($chmenu4 >= 1 || $_SESSION['jabatan'] == 'admin'){ ?>

                        <li class="treeview">
                            <a href="#"> <i class="glyphicon glyphicon-th-list"></i> <span>Barang</span> <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i> </span> </a>
               <ul class="treeview-menu">
                               
                                      <li>
                                    <a href="add_barang"><i class="fa fa-circle-o"></i>Tambah Barang</a>
                                                  </li>
                                                   <li>
                                    <a href="barang"><i class="fa fa-circle-o"></i>Data Barang</a>
                                </li>
                                 
                                      <li>
                                    <a href="cetak_barcode"><i class="fa fa-circle-o"></i>Cetak Barcode</a>
                                  </li>

                            </ul>
                        </li>



<?php }else{}



if($chmenu3 >= 1 || $_SESSION['jabatan'] == 'admin'){ ?>

                        <li class="treeview">
                            <a href="#"> <i class="glyphicon glyphicon-tag"></i> <span>Atribut Barang</span> <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i> </span> </a>
               <ul class="treeview-menu">
                                <li>
                                    <a href="kategori"><i class="fa fa-circle-o"></i>Kategori</a>
                                </li>
                                 
                                 <li>
                                    <a href="merek"><i class="fa fa-circle-o"></i>Brand</a>
                                 </li>

                                 <li>
                                    <a href="satuan"><i class="fa fa-circle-o"></i>Satuan</a>
                                 </li>
                            </ul>
                        </li>


  <?php }else{}


if($chmenu5 >= 1 || $_SESSION['jabatan'] == 'admin'){ ?>

         <li class="treeview">
                            <a href="#"> <i class="glyphicon glyphicon-th-list"></i> <span>Aktivitas</span> <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i> </span> </a>
               <ul class="treeview-menu">
                                
                                    
                  <li>
                      <a href="stok_masuk"><i class="fa fa-circle-o"></i>Barang Masuk</a>
                    </li>
                  <li>
                       <a href="stok_keluar"><i class="fa fa-circle-o"></i>Barang Keluar</a>
                    </li>
                     <li>
                       <a href="surat_kelola"><i class="fa fa-circle-o"></i>Surat Jalan</a>
                    </li>

                    <li>
                       <a href="stok_sesuaikan"><i class="fa fa-circle-o"></i>Penyesuaian</a>
                    </li>
                    

                            </ul>
                        </li>              

    <?php }else{}

if($chmenu6 >= 1 || $_SESSION['jabatan'] == 'admin'){ ?>

                       
    <?php }else{}

if($chmenu7 >= 1 || $_SESSION['jabatan'] == 'admin'){ ?>

                      


<?php }else{}
              if($chmenu8 >= 1 || $_SESSION['jabatan'] == 'admin'){ ?>

    <li class="treeview">
          <a href="#"> <i class="glyphicon glyphicon-inbox"></i> <span>Stok</span> <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i> </span> </a>
            <ul class="treeview-menu">
                <li>
                    <a href="stok_barang"><i class="fa fa-circle-o"></i>Data Stok</a>
                  </li>
                                  
                   
                      <li>
                        <a href="stok_menipis"><i class="fa fa-circle-o"></i>Stok Menipis</a>
                      </li>
                       <li>
                      <a href="mutasi"><i class="fa fa-circle-o"></i>Mutasi</a>
                    </li>
                   
                </ul>
              </li>


<?php }else{}
  if($chmenu9 >= 1 || $_SESSION['jabatan'] == 'admin'){ ?>


 <li class="treeview">
                            <a href="#"> <i class="glyphicon glyphicon-folder-close"></i> <span>Laporan</span> <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i> </span> </a>
               <ul class="treeview-menu">
                                <li>
                                    <a href="laporan_stok"><i class="fa fa-circle-o"></i>Stok</a>
                                </li>
                                                    <li>
                                    <a href="laporan_penyesuaian"><i class="fa fa-circle-o"></i>Daftar Penyesuaian</a>
                                                  </li>

                                                  <li>
                                    <a href="laporan_arus"><i class="fa fa-circle-o"></i>Keluar Masuk</a>
                                                  </li>
                            </ul>
                        </li>


                         
<?php }else{}
if($chmenu2 >= 1 || $_SESSION['jabatan'] == 'admin'){ ?>

                        <li class="treeview">
                            <a href="#"> <i class="glyphicon glyphicon-folder-close"></i> <span>Supplier & Pelanggan</span> <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i> </span> </a>
               <ul class="treeview-menu">
                                <li>
                                    <a href="supplier"><i class="fa fa-circle-o"></i>Data Supplier</a>
                                </li>
<li>
                                    <a href="add_supplier"><i class="fa fa-circle-o"></i>Tambah Supplier</a>
                                                  </li>
                                                   <li>
                                    <a href="customer"><i class="fa fa-circle-o"></i>Data Pelanggan</a>
                                </li>
                                 <li>
                                    <a href="add_customer"><i class="fa fa-circle-o"></i>Tambah pelanggan</a>
                                </li>
                            </ul>
                        </li>
<?php }else{}


if($chmenu1 >= 1 || $_SESSION['jabatan'] == 'admin'){ ?>


              <li class="treeview">
                            <a href=""> <i class="glyphicon glyphicon-cog"></i> <span>Manajemen User</span> <span class="pull-right-container"> </span> </a>
                               <ul class="treeview-menu">
                                <li>
                                    <a href="admin"><i class="fa fa-circle-o"></i>Kelola User</a>
                                </li>
                <li>
                <a href="add_jabatan"><i class="fa fa-circle-o"></i>Jabatan User</a>
                               </li>

                                
                                                 
                            </ul>
                        </li>
<?php }else{}

  

if($chmenu10 >= 1 || $_SESSION['jabatan'] == 'admin'){ ?>


              <li class="treeview">
                            <a href=""> <i class="glyphicon glyphicon-cog"></i> <span>Pengaturan</span> <span class="pull-right-container"> </span> </a>
                               <ul class="treeview-menu">
                                <li>
                                    <a href="set_general"><i class="fa fa-circle-o"></i>General Setting</a>
                                </li>
                <li>
                <a href="set_themes"><i class="fa fa-circle-o"></i>Theme Setting</a>
                               </li>

                               
                                                                   
                
                                                  <li>
                                <a href="backup"><i class="fa fa-circle-o"></i>Backup & Restore</a>
                                                                   </li>
                                              
                            </ul>
                        </li>
<?php }else{} 
 ?>


                    </ul>

                </section>
                <!-- /.sidebar -->
            </aside>
