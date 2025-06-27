import { Component, OnInit, ViewChild } from '@angular/core';
import { Router } from '@angular/router';
import { CategoryService } from '../../services/category.service';
import { MatTableDataSource } from '@angular/material/table';
import { FormControl } from '@angular/forms';
import { MatPaginator } from '@angular/material/paginator';
import { MatSort } from '@angular/material/sort';

@Component({
  selector: 'app-categorys',
  standalone: false,
  templateUrl: './categorys.component.html',
  styleUrls: ['./categorys.component.scss']
})
export class CategorysComponent implements OnInit {
  searchControl = new FormControl('');
  displayedColumns: string[] = ['name', 'descripcion', 'style', 'actions'];
  categories = new MatTableDataSource<any>();

  @ViewChild(MatPaginator) paginator!: MatPaginator;
  @ViewChild(MatSort) sort!: MatSort;

  constructor(
    private categoryService: CategoryService,
    private router: Router
  ) { }

  ngOnInit(): void {
    this.loadCategories();

    this.searchControl.valueChanges.subscribe(value => {
      this.applyFilter(value ?? '');
    });
  }

  loadCategories(): void {
    this.categoryService.getAll().subscribe(data => {
      this.categories.data = data;
      this.categories.paginator = this.paginator;
      this.categories.sort = this.sort;
    });
  }

  applyFilter(value: string): void {
    this.categories.filter = value.trim().toLowerCase();
  }

  createCategory(): void {
    this.router.navigate(['/categories/create']);
  }

  editCategory(category: any): void {
    this.router.navigate(['/categories/edit', category.id]);
  }

  deleteCategory(id: number): void {
    if (confirm('¿Estás seguro de eliminar esta categoría?')) {
      this.categoryService.delete(id).subscribe(() => this.loadCategories());
    }
  }

  goBack(): void {
    this.router.navigate(['/dashboard']);
  }
}
