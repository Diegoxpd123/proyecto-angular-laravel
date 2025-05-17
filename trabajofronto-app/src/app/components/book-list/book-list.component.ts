import { Component, OnInit } from '@angular/core';
import { Book } from '../../models/book.model';
import { BookService } from '../../services/book.service';
import  Toastify from 'toastify-js';

@Component({
  selector: 'app-book-list',
  standalone: false,
  templateUrl: './book-list.component.html',
  styleUrl: './book-list.component.scss'
})
export class BookListComponent implements OnInit  {
    books: Book[] = [];
    total: number = 0;

  constructor(
    private bookService: BookService
  ) { }

  ngOnInit(): void {
     this.loadDataIntoTable();
  }

  deleteBook(id: number): void {
    this.bookService.deleteBook(id).subscribe(response => {
      this.showSuccessToast('Book eliminado');
      this.books = this.books.filter(book => book.id != id);
      this.calculateTotal();
    });
  }

   private loadDataIntoTable(): void {
    this.bookService.getBooks().subscribe(books => {
      this.books = books;
      this.calculateTotal();
    });
  }

  private calculateTotal(): void {
    this.total = this.books.length;

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
