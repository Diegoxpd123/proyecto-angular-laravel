import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators, FormArray } from '@angular/forms';
import { Router } from '@angular/router';
import { GenreService } from '../../services/genre.service';
import { StyleService } from '../../services/style.service';
import { CategoryService } from '../../services/category.service';
import { ProductService } from '../../services/product.service';
import { ProductDetailService } from '../../services/product-detail.service';

@Component({
  selector: 'app-product-create',
  standalone: false,
  templateUrl: './product-create.component.html',
  styleUrls: ['./product-create.component.scss']
})
export class ProductCreateComponent implements OnInit {
  form!: FormGroup;
  genres: any[] = [];
  styles: any[] = [];
  categories: any[] = [];
  filteredStyles: any[] = [];
  filteredCategories: any[] = [];
  selectedImages: File[] = [];
   imagePreviews: string[] = [];
  selectedFiles: File[] = [];


  constructor(
    private fb: FormBuilder,
    private router: Router,
    private genreService: GenreService,
    private styleService: StyleService,
    private categoryService: CategoryService,
    private productdetailService: ProductDetailService,
    private productService: ProductService
  ) { }

  ngOnInit(): void {
    this.form = this.fb.group({
      name: ['', Validators.required],
      categoriaid: [null, Validators.required],
      image1: [''],
      image2: [''],
      image3: [''],
      genreid: [null],
      styleid: [null],
      detalles: this.fb.array([])
    });

    this.loadGenres();
  }

  get detalles(): FormArray {
    return this.form.get('detalles') as FormArray;
  }



  addDetalle(): void {
    this.detalles.push(
      this.fb.group({
        talla: ['', Validators.required],
        stock: [0, Validators.required],
        cantidadmayor: [0],
        precio: [0, Validators.required],
        preciomayor: [0],
        preciosale: [0],
        issale: [false]
      })
    );
  }

  removeDetalle(index: number): void {
    this.detalles.removeAt(index);
  }

  loadGenres(): void {
    this.genreService.getAll().subscribe(data => this.genres = data);
  }

  onGenreChange(): void {
    const genreId = this.form.get('genreid')?.value;
    this.styleService.getAll().subscribe(data => {
      this.styles = data.filter(s => s.genreid === genreId);
    });
  }

  onStyleChange(): void {
    const styleId = this.form.get('styleid')?.value;
    this.categoryService.getAll().subscribe(data => {
      this.categories = data.filter(c => c.styleid === styleId);
    });
  }

  async onSubmit() {
    if (this.form.invalid) return;

    const { name, categoriaid, detalles } = this.form.value;

    const imageNames: string[] = [];

    // Subir imágenes a Cloudflare R2
    for (const file of this.selectedFiles) {
      const filename = `${Date.now()}-${file.name}`;
      imageNames.push(filename);
      await this.uploadImageToR2(file, filename); // función que se detalla abajo
    }

    const [image1, image2, image3] = imageNames;

    this.productService.create({ name, categoriaid, image1, image2, image3 }).subscribe(product => {
      const productId = product.id;

      const detalleRequests = detalles.map((detalle: any) => {
        return this.productdetailService.create({ ...detalle, productoid: productId });
      });

      Promise.all(detalleRequests).then(() => {
        this.router.navigate(['/products']);
      });
    });
  }


  async uploadImageToR2(file: File, filename: string) {
  const formData = new FormData();
  formData.append('file', file, filename);

  await fetch('https://moving-firefly-neatly.ngrok-free.app/api/upload-image', {
    method: 'POST',
    headers: {
      Authorization: `Bearer ${localStorage.getItem('token')}`,
      'X-Tenant-ID': localStorage.getItem('tenant_id') || ''
    },
    body: formData
  });
}




  onImageSelect(event: Event): void {
    const target = event.target as HTMLInputElement;
    const files = target.files;

    if (!files) return;

    const fileArray = Array.from(files);
    const totalImages = this.imagePreviews.length + fileArray.length;

    if (totalImages > 3) {
      alert('Solo puedes subir hasta 3 imágenes.');
      return;
    }

    for (let file of fileArray) {
      this.selectedFiles.push(file);

      const reader = new FileReader();
      reader.onload = (e: any) => {
        this.imagePreviews.push(e.target.result); // base64 para mostrar en preview
      };
      reader.readAsDataURL(file);
    }
  }

  removeImage(imageUrl: string): void {
    const index = this.imagePreviews.indexOf(imageUrl);
    if (index > -1) {
      this.imagePreviews.splice(index, 1);
      this.selectedFiles.splice(index, 1);
    }
  }

  cancel(): void {
    this.router.navigate(['/products']);
  }
}
