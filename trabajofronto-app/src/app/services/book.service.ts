import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { Book } from '../models/book.model';

@Injectable({
  providedIn: 'root'
})
export class BookService {

  private readonly API_URL = 'http://127.0.0.1:8000/api';

    constructor(
      private http: HttpClient
    ) { }

    getBooks(): Observable<Book[]> {
      return this.http.get<Book[]>(`${this.API_URL}/books`);
    }

    getBook(id: number): Observable<Book> {
      return this.http.get<Book>(`${this.API_URL}/books/${id}`);
    }

    createBook(data: Book): Observable<Book> {
      return this.http.post<Book>(`${this.API_URL}/books`, data);
    }

    updateBook(id: number, data: Book): Observable<Book> {
      return this.http.put<Book>(`${this.API_URL}/books/${id}`, data);
    }

    deleteBook(id: number): Observable<any> {
      return this.http.delete<any>(`${this.API_URL}/books/${id}`);
    }
}
