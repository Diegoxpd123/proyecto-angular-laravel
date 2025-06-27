import { Component, OnInit, ViewChild } from '@angular/core';
import { BranchService } from '../../services/branch.service';
import { MatTableDataSource } from '@angular/material/table';
import { Router } from '@angular/router';
import { MatPaginator } from '@angular/material/paginator';

@Component({
  standalone: false,
  selector: 'app-branches',
  templateUrl: './branches.component.html',
  styleUrls: ['./branches.component.scss']
})
export class BranchesComponent implements OnInit {
  branches = new MatTableDataSource<any>([]);
  displayedColumns: string[] = ['name', 'descripcion', 'actions'];
  searchText: string = '';

  @ViewChild(MatPaginator) paginator!: MatPaginator;

  constructor(
    private branchService: BranchService,
    private router: Router
  ) {}

  ngOnInit(): void {
    this.loadBranches();
  }

  loadBranches(): void {
    this.branchService.getAll().subscribe({
      next: (res) => {
        this.branches.data = res;
        this.branches.paginator = this.paginator;
      },
      error: (err) => console.error('Error cargando sucursales', err)
    });
  }

  applyFilter(): void {
    this.branches.filter = this.searchText.trim().toLowerCase();
  }

  createBranch(): void {
    this.router.navigate(['/branches/create']);
  }

  editBranch(branch: any): void {
    this.router.navigate(['/branches/edit', branch.id]);
  }

  deleteBranch(id: number): void {
    if (confirm('¿Estás seguro de eliminar esta sucursal?')) {
      this.branchService.delete(id).subscribe({
        next: () => this.loadBranches(),
        error: () => alert('Error al eliminar sucursal')
      });
    }
  }

  goBack(): void {
    this.router.navigate(['/dashboard']);
  }
}
