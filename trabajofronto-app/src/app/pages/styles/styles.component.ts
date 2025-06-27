import { Component, OnInit, ViewChild } from '@angular/core';
import { Router } from '@angular/router';
import { StyleService } from '../../services/style.service';
import { MatTableDataSource } from '@angular/material/table';
import { MatPaginator } from '@angular/material/paginator';
import { MatSort } from '@angular/material/sort';
import { FormControl } from '@angular/forms';

@Component({
  selector: 'app-styles',
  standalone: false,
  templateUrl: './styles.component.html',
  styleUrls: ['./styles.component.scss']
})
export class StylesComponent implements OnInit {
  searchControl = new FormControl('');
  displayedColumns: string[] = ['name', 'descripcion', 'genre', 'actions'];
  styles = new MatTableDataSource<any>([]);

  @ViewChild(MatPaginator) paginator!: MatPaginator;
  @ViewChild(MatSort) sort!: MatSort;

  constructor(
    private styleService: StyleService,
    private router: Router
  ) {}

  ngOnInit(): void {
    this.loadStyles();

    this.searchControl.valueChanges.subscribe(value => {
      this.applyFilter(value ?? '');
    });
  }

  loadStyles(): void {
    this.styleService.getAll().subscribe(data => {
      this.styles.data = data;
      this.styles.paginator = this.paginator;
      this.styles.sort = this.sort;
    });
  }

  applyFilter(filterValue: string): void {
    this.styles.filter = filterValue.trim().toLowerCase();
  }

  createStyle(): void {
    this.router.navigate(['/styles/create']);
  }

  editStyle(style: any): void {
    this.router.navigate(['/styles/edit', style.id]);
  }

  deleteStyle(id: number): void {
    if (confirm('¿Estás seguro de eliminar este estilo?')) {
      this.styleService.delete(id).subscribe(() => this.loadStyles());
    }
  }

  goBack(): void {
    this.router.navigate(['/dashboard']);
  }
}
