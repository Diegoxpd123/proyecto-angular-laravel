import { Component, OnInit, ViewChild } from '@angular/core';
import { Router } from '@angular/router';
import { ProductService } from '../../services/product.service';
import { MatTableDataSource } from '@angular/material/table';
import { MatPaginator } from '@angular/material/paginator';
import { MatSort } from '@angular/material/sort';
import { FormControl } from '@angular/forms';

@Component({
  selector: 'app-products',
  standalone: false,
  templateUrl: './products.component.html',
  styleUrls: ['./products.component.scss']
})
export class ProductsComponent implements OnInit {
  searchControl = new FormControl('');
  displayedColumns: string[] = ['name', 'category', 'details', 'actions'];
  products = new MatTableDataSource<any>([]);

  @ViewChild(MatPaginator) paginator!: MatPaginator;
  @ViewChild(MatSort) sort!: MatSort;

  constructor(
    private productService: ProductService,
    private router: Router
  ) {}

  ngOnInit(): void {
    this.loadProducts();

    this.searchControl.valueChanges.subscribe(value => {
      this.applyFilter(value ?? '');
    });
  }

  loadProducts(): void {
    this.productService.getAll().subscribe(data => {
      this.products.data = data;
      this.products.paginator = this.paginator;
      this.products.sort = this.sort;
    });
  }

  applyFilter(filterValue: string): void {
    this.products.filter = filterValue.trim().toLowerCase();
  }

  createProduct(): void {
    this.router.navigate(['/products/create']);
  }

  editProduct(product: any): void {
    this.router.navigate(['/products/edit', product.id]);
  }

  deleteProduct(id: number): void {
    if (confirm('¿Estás seguro de eliminar este producto?')) {
      this.productService.delete(id).subscribe(() => this.loadProducts());
    }
  }

  goBack(): void {
    this.router.navigate(['/dashboard']);
  }

  getDetailsCount(product: any): number {
    return product.detalles?.length || 0;
  }

  getCategoryName(product: any): string {
    return product.category?.name || 'Sin categoría';
  }
}
