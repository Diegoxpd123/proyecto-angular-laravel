import { Component, OnInit } from '@angular/core';
import { BranchService } from '../../services/branch.service';
import { MatTableDataSource } from '@angular/material/table';
import { Router } from '@angular/router';

@Component({
  standalone: false,
  selector: 'app-branches',
  templateUrl: './branches.component.html',
  styleUrls: ['./branches.component.scss']
})
export class BranchesComponent implements OnInit {
  branches = new MatTableDataSource<any>([]);
  displayedColumns: string[] = ['name', 'descripcion', 'actions'];

  constructor(private branchService: BranchService, private router: Router) { }

  ngOnInit(): void {
    this.loadBranches();
  }

  goBack(): void {
    this.router.navigate(['/dashboard']);
  }

  loadBranches(): void {
    this.branchService.getAll().subscribe({
      next: (res) => this.branches.data = res,
      error: (err) => console.error('Error cargando sucursales', err)
    });
  }

  createBranch() {
    // lógica para crear (modal, navegar o inline)
    console.log('Crear nueva sucursal');
  }

  editBranch(branch: any) {
    // lógica para editar (modal, navegar o inline)
    console.log('Editar sucursal', branch);
  }

  deleteBranch(id: number) {
    if (confirm('¿Estás seguro de eliminar esta sucursal?')) {
      this.branchService.delete(id).subscribe({
        next: () => this.loadBranches(),
        error: () => alert('Error al eliminar sucursal')
      });
    }
  }
}
