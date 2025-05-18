import { Component, OnInit } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import { BookService } from '../../services/book.service';
import Toastify from 'toastify-js';

@Component({
  selector: 'app-book-form',
  standalone: false,
  templateUrl: './book-form.component.html',
  styleUrl: './book-form.component.scss'
})
export class BookFormComponent implements OnInit {
  bookId?: number;
  hoy = new Date().toISOString().substring(0, 10);

  selectedFile?: File;
  previewUrl?: string;

  maxLength: number = 13;

  bookForm: FormGroup = new FormGroup({
    isbn: new FormControl('', [Validators.required, Validators.maxLength(this.maxLength)]),
    name: new FormControl('', [Validators.required, Validators.maxLength(100)]),
    stock: new FormControl('', [Validators.required, Validators.min(0)]),
    price: new FormControl('', [Validators.required, Validators.min(0)]),
    image: new FormControl(''),
    is_actived: new FormControl(1),
    is_deleted: new FormControl(0),
    created_at: new FormControl(this.hoy),
    updated_at: new FormControl(this.hoy),
  });

  constructor(
    private bookService: BookService,
    private router: Router,
    private route: ActivatedRoute
  ) {}

  ngOnInit(): void {
    this.loadDataIntoForm();
  }

  onFileChange(event: Event): void {
    const input = event.target as HTMLInputElement;
    if (input.files && input.files[0]) {
      this.selectedFile = input.files[0];
      const reader = new FileReader();
      reader.onload = () => this.previewUrl = reader.result as string;
      reader.readAsDataURL(this.selectedFile);
    }
  }

  saveBook(): void {
    if (this.bookForm.invalid) return;

    const bookData = { ...this.bookForm.value };

    if (this.selectedFile) {
      this.convertFileToBase64(this.selectedFile).then(base64 => {
        bookData.image = base64;
        this.sendBookData(bookData);
      }).catch(error => {
        console.error("Error al convertir la imagen:", error);
      });
    } else {
      this.sendBookData(bookData);
    }
  }

  private sendBookData(bookData: any): void {
    const request = this.bookId
      ? this.bookService.updateBook(this.bookId, bookData)
      : this.bookService.createBook(bookData);

    request.subscribe({
      next: () => {
        this.showSuccessToast(this.bookId ? "Libro actualizado con éxito" : "Libro agregado con éxito");
        this.router.navigateByUrl('/books');
      },
      error: (err) => {
        console.error(err);
      }
    });
  }

  private convertFileToBase64(file: File): Promise<string> {
    return new Promise((resolve, reject) => {
      const reader = new FileReader();
      reader.readAsDataURL(file);
      reader.onload = () => {
        if (typeof reader.result === 'string') {
          resolve(reader.result);
        } else {
          reject('No se pudo leer el archivo como string');
        }
      };
      reader.onerror = error => reject(error);
    });
  }

  hasError(field: string): boolean {
    const control = this.bookForm.get(field);
    return !!(control && control.invalid && (control.touched || control.dirty));
  }

  getCurrentError(field: string): string {
    const errorsObject = this.bookForm.get(field)?.errors ?? {};
    const errors = Object.keys(errorsObject);
    return errors.length ? errors[0] : '';
  }

  getFormTitle(): string {
    return this.bookId ? 'Editar libro' : 'Nuevo libro';
  }

  private loadDataIntoForm(): void {
    this.bookId = Number(this.route.snapshot.paramMap.get('id'));

    if (this.bookId) {
      this.bookService.getBook(this.bookId).subscribe(book => {
        this.bookForm.patchValue(book);
        this.previewUrl = book.image;
      });
    }
  }

  private showSuccessToast(message: string): void {
    Toastify({
      text: message,
      close: true,
      gravity: "bottom",
      position: "center",
      stopOnFocus: true,
      style: {
        background: "#189586",
      }
    }).showToast();
  }
}
